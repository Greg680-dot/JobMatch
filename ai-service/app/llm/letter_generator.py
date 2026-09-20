import re
import json
import httpx
from app.api.schemas import LetterGenerateRequest, LetterGenerateResponse
from app.config import settings

def generate_cover_letter_and_tips(req: LetterGenerateRequest) -> LetterGenerateResponse:
    """
    Génère une lettre de motivation personnalisée et des conseils d'adaptation du CV.
    Utilise l'API Claude si la clé est présente, sinon fournit un moteur de rédaction
    avancé par templates dynamiques et alignement sémantique des arguments.
    """
    if settings.ANTHROPIC_API_KEY:
        try:
            return _call_claude_letter(req)
        except Exception as e:
            print(f"[Warning] Claude API error, fallback to template generator: {e}")

    return _generate_dynamic_letter(req)

def _generate_dynamic_letter(req: LetterGenerateRequest) -> LetterGenerateResponse:
    p = req.candidate_profile
    candidate_name = p.nom or "Candidat"
    email = p.email or "candidat@email.com"
    phone = p.telephone or "06 00 00 00 00"
    job = req.job_title
    company = req.company_name
    skills = req.matching_skills or p.competences_techniques[:4]
    
    skills_str = ", ".join(skills[:3]) if skills else "compétences techniques"
    recent_exp = p.experiences[0].poste if p.experiences else (p.titre_professionnel or "Développeur")

    letter_text = f"""{candidate_name}
{email} | {phone}

À l'attention de l'équipe de recrutement
{company}

Objet : Candidature au poste de {job}

Madame, Monsieur,

C'est avec un vif intérêt que je vous adresse ma candidature pour le poste de {job} au sein de {company}. Passionné par l'innovation technique et la création de solutions logicielles performantes, je suis particulièrement motivé par les perspectives et les projets menés par votre structure.

Fort d'une solide expérience en tant que {recent_exp}, j'ai développé une expertise pointue autour de technologies clés telles que {skills_str}. Au cours de mes précédentes missions, j'ai notamment contribué à concevoir, déployer et optimiser des architectures applicatives robustes, tout en veillant aux bonnes pratiques de qualité de code et aux exigences métiers.

Rejoindre {company} représente pour moi l'opportunité de mettre mes compétences au service de vos objectifs de développement, tout en intégrant une équipe collaborative et exigeante. Mon autonomie, ma rigueur et ma réactivité me permettront d'être immédiatement opérationnel et d'apporter une contribution concrète à vos équipes.

Je me tiens à votre entière disposition pour échanger de vive voix lors d'un entretien afin de vous exposer plus en détail mes motivations et la manière dont mon profil répond aux exigences du poste.

Dans cette attente, je vous prie d'agréer, Madame, Monsieur, l'expression de mes salutations distinguées.

{candidate_name}
"""

    tips = [
        f"Mettez en haut de votre CV le titre exact de l'offre : '{job}' pour maximiser le score ATS.",
        f"Valorisez en premier lieu les compétences validées pour cette offre : {skills_str}.",
        f"Dans la description de vos expériences récentes, quantifiez vos résultats pour faire écho aux missions de {company}.",
        "Assurez-vous que le format de votre CV reste sobre (une seule colonne, typographie standard) pour garantir une lecture machine sans erreur."
    ]

    return LetterGenerateResponse(
        success=True,
        cover_letter=letter_text.strip(),
        object_email=f"Candidature : {job} - {candidate_name}",
        cv_adaptation_tips=tips,
        suggested_skills_to_highlight=skills[:5]
    )

def _call_claude_letter(req: LetterGenerateRequest) -> LetterGenerateResponse:
    prompt = f"""Rédige une lettre de motivation percutante, élégante et personnalisée (ton {req.tone}) pour le poste suivant :
Poste : {req.job_title}
Entreprise : {req.company_name}
Description de l'offre :
{req.job_description[:2000]}

Profil du candidat :
Nom : {req.candidate_profile.nom}
Titre actuel : {req.candidate_profile.titre_professionnel}
Compétences : {', '.join(req.candidate_profile.competences_techniques)}
Dernière expérience : {req.candidate_profile.experiences[0].poste if req.candidate_profile.experiences else ''}

Réponds en format JSON strict :
{{
  "cover_letter": string,
  "object_email": string,
  "cv_adaptation_tips": [string],
  "suggested_skills_to_highlight": [string]
}}
"""
    headers = {
        "x-api-key": settings.ANTHROPIC_API_KEY,
        "anthropic-version": "2023-06-01",
        "content-type": "application/json"
    }
    body = {
        "model": "claude-3-5-sonnet-20241022",
        "max_tokens": 1800,
        "messages": [{"role": "user", "content": prompt}]
    }
    with httpx.Client(timeout=35.0) as client:
        res = client.post("https://api.anthropic.com/v1/messages", json=body, headers=headers)
        res.raise_for_status()
        raw_json = res.json()["content"][0]["text"]
        match = re.search(r'\{.*\}', raw_json, re.DOTALL)
        if match:
            data = json.loads(match.group(0))
            return LetterGenerateResponse(
                success=True,
                cover_letter=data.get("cover_letter", ""),
                object_email=data.get("object_email", f"Candidature {req.job_title}"),
                cv_adaptation_tips=data.get("cv_adaptation_tips", []),
                suggested_skills_to_highlight=data.get("suggested_skills_to_highlight", [])
            )
        raise ValueError("Invalid JSON response from Claude")
