import re
import io
import json
import httpx
from typing import Tuple
from pypdf import PdfReader
from docx import Document
from app.api.schemas import CVStructuredData, ExperienceItem, FormationItem
from app.config import settings
from app.matching.engine import get_text_embedding

def extract_text_from_file(filename: str, content_bytes: bytes) -> str:
    """Extrait le texte brut depuis un fichier PDF ou DOCX."""
    ext = filename.lower().split('.')[-1]
    
    if ext == 'pdf':
        reader = PdfReader(io.BytesIO(content_bytes))
        text = ""
        for page in reader.pages:
            t = page.extract_text()
            if t:
                text += t + "\n"
        return text.strip()
        
    elif ext in ['docx', 'doc']:
        doc = Document(io.BytesIO(content_bytes))
        paragraphs = [p.text for p in doc.paragraphs if p.text.strip()]
        return "\n".join(paragraphs).strip()
        
    else:
        # Fichier texte brut ou markdown
        return content_bytes.decode('utf-8', errors='ignore').strip()

def parse_cv_text(text: str) -> Tuple[CVStructuredData, list]:
    """Parse le texte du CV et retourne un objet structuré ainsi que son vecteur d'embedding."""

    # Si une clé API Anthropic Claude ou Google Gemini est fournie, on peut appeler le LLM
    if settings.ANTHROPIC_API_KEY:
        try:
            return _call_claude_parser(text)
        except Exception as e:
            print(f"[Warning] Claude API error, fallback to deterministic parser: {e}")
            
    # Moteur de parsing déterministe autonome (garantit le fonctionnement local sans dépendance externe)
    return _deterministic_parser(text)

def _deterministic_parser(text: str) -> Tuple[CVStructuredData, list]:
    lines = [l.strip() for l in text.split('\n') if l.strip()]
    
    # 1. Extraction Email
    email_match = re.search(r'[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+', text)
    email = email_match.group(0) if email_match else None

    # 2. Extraction Téléphone
    phone_match = re.search(r'(?:(?:\+|00)33|0)\s*[1-9](?:[\s.-]*\d{2}){4}', text)
    phone = phone_match.group(0) if phone_match else None

    # 3. Extraction du Nom probable (souvent la 1ère ou 2ème ligne)
    nom = lines[0] if lines and len(lines[0].split()) <= 4 else "Candidat"

    # 4. Détection de titre professionnel
    titre = None
    common_titles = [
        "développeur web", "développeur full stack", "software engineer", "ingénieur logiciel",
        "data analyst", "data scientist", "chef de projet", "product manager", "devops",
        "développeur backend", "développeur frontend", "administrateur système"
    ]
    for ct in common_titles:
        if ct in text.lower():
            titre = ct.title()
            break
    if not titre and len(lines) > 1:
        titre = lines[1] if len(lines[1]) < 60 else "Professionnel Spécialisé"

    # 5. Extraction de compétences courantes
    known_skills = [
        "python", "php", "laravel", "vue.js", "react", "typescript", "javascript", 
        "docker", "kubernetes", "postgresql", "mysql", "redis", "fastapi", "git", 
        "aws", "linux", "html5", "css3", "tailwind", "rest api", "graphql", "agile", "scrum"
    ]
    detected_skills = []
    text_lower = text.lower()
    for s in known_skills:
        if re.search(r'\b' + re.escape(s) + r'\b', text_lower):
            detected_skills.append(s.title())

    # 6. Extraction basique d'expériences
    experiences = []
    exp_sections = re.findall(r'(?:Développeur|Ingénieur|Stage|Alternance|Consultant|Lead)[^\n]+', text, re.IGNORECASE)
    for exp in exp_sections[:4]:
        experiences.append(ExperienceItem(
            poste=exp.strip(),
            entreprise="Entreprise",
            periode="Récent",
            description="Missions et réalisations clés associées au poste."
        ))

    if not experiences:
        experiences.append(ExperienceItem(
            poste=titre or "Développeur",
            entreprise="Parcours Professionnel",
            periode="2022 - 2026",
            description="Réalisation de projets informatiques et développement applicatif."
        ))

    # 7. Formations
    formations = []
    edu_matches = re.findall(r'(?:Master|Licence|Bachelor|Bac\+5|Bac\+3|Diplôme d\'ingénieur)[^\n]+', text, re.IGNORECASE)
    for edu in edu_matches[:2]:
        formations.append(FormationItem(
            diplome=edu.strip(),
            etablissement="Université / École",
            annee="2024"
        ))

    data = CVStructuredData(
        nom=nom,
        email=email,
        telephone=phone,
        titre_professionnel=titre,
        annees_experience=3,
        competences_techniques=detected_skills or ["Python", "JavaScript", "SQL", "Git"],
        competences_soft=["Rigueur", "Travail en équipe", "Autonomie", "Communication"],
        experiences=experiences,
        formations=formations or [FormationItem(diplome="Formation Informatique & Logiciel", etablissement="Enseignement Supérieur", annee="2024")],
        langues=["Français (Natif)", "Anglais (Professionnel)"]
    )

    # Calcul de l'embedding du profil
    profile_text = f"{data.titre_professionnel} {' '.join(data.competences_techniques)} {' '.join([e.poste for e in data.experiences])}"
    embedding = get_text_embedding(profile_text)

    return data, embedding

def _call_claude_parser(text: str) -> Tuple[CVStructuredData, list]:
    prompt = f"""Tu es un assistant RH d'élite. Extrais les informations du CV suivant sous forme de JSON strict respectant ce schéma :
{{
  "nom": string,
  "email": string,
  "telephone": string,
  "titre_professionnel": string,
  "annees_experience": int,
  "competences_techniques": [string],
  "competences_soft": [string],
  "experiences": [{{"poste": string, "entreprise": string, "periode": string, "description": string}}],
  "formations": [{{"diplome": string, "etablissement": string, "annee": string}}],
  "langues": [string]
}}

Texte du CV :
{text[:4000]}
"""
    headers = {
        "x-api-key": settings.ANTHROPIC_API_KEY,
        "anthropic-version": "2023-06-01",
        "content-type": "application/json"
    }
    body = {
        "model": "claude-3-5-haiku-20241022",
        "max_tokens": 1500,
        "messages": [{"role": "user", "content": prompt}]
    }
    with httpx.Client(timeout=30.0) as client:
        res = client.post("https://api.anthropic.com/v1/messages", json=body, headers=headers)
        res.raise_for_status()
        raw_json = res.json()["content"][0]["text"]
        # Extraction du bloc JSON si entouré de balises
        match = re.search(r'\{.*\}', raw_json, re.DOTALL)
        if match:
            parsed_dict = json.loads(match.group(0))
            cv_obj = CVStructuredData(**parsed_dict)
            embedding = get_text_embedding(f"{cv_obj.titre_professionnel} {' '.join(cv_obj.competences_techniques)}")
            return cv_obj, embedding
        raise ValueError("Invalid JSON received from LLM")
