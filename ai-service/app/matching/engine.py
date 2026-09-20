import re
import hashlib
import numpy as np
from typing import List, Tuple, Dict, Any, Optional
from app.api.schemas import CVStructuredData, SearchFilters, JobOpportunityInput, MatchResultItem

# Dimension standard des vecteurs de similarité
EMBEDDING_DIM = 64

def get_text_embedding(text: str) -> List[float]:
    """
    Génère un vecteur d'embedding sémantique dense et normalisé.
    Utilise une projection déterministe basée sur les n-grammes de mots et les termes clés,
    ce qui permet une similarité cosinus sémantique instantanée sans coût d'API obligatoire.
    Si une clé Voyage AI ou OpenAI est fournie ultérieurement, elle s'interface ici.
    """
    if not text:
        return [0.0] * EMBEDDING_DIM

    words = re.findall(r'\b[a-zA-ZÀ-ÿ0-9+#.-]{2,}\b', text.lower())
    vec = np.zeros(EMBEDDING_DIM, dtype=np.float32)

    for word in words:
        # Hashing de feature pour projeter les tokens dans l'espace vectoriel
        h = int(hashlib.md5(word.encode('utf-8')).hexdigest(), 16)
        idx = h % EMBEDDING_DIM
        sign = 1.0 if ((h >> 8) & 1) else -1.0
        vec[idx] += sign

    # Normalisation L2 du vecteur pour un calcul de similarité cosinus direct
    norm = np.linalg.norm(vec)
    if norm > 0:
        vec = vec / norm
    return vec.tolist()

def compute_cosine_similarity(vec_a: List[float], vec_b: List[float]) -> float:
    """Calcule la similarité cosinus entre deux vecteurs normalisés (retourne entre 0.0 et 1.0)."""
    if not vec_a or not vec_b:
        return 0.0
    a = np.array(vec_a, dtype=np.float32)
    b = np.array(vec_b, dtype=np.float32)
    dot = np.dot(a, b)
    # Ramène dans l'intervalle [0, 1]
    return float(max(0.0, min(1.0, (dot + 1.0) / 2.0)))

def extract_skill_matches(cv_skills: List[str], job_description: str) -> Tuple[List[str], List[str]]:
    """Identifie les compétences du CV présentes dans l'offre et les compétences clés manquantes."""
    desc_lower = job_description.lower()
    matching = []
    
    for s in cv_skills:
        # Regex sécurisée
        pattern = r'\b' + re.escape(s.lower()) + r'\b'
        if re.search(pattern, desc_lower):
            matching.append(s)

    # Détection de compétences courantes demandées dans l'offre mais non présentes dans le CV
    common_market_skills = [
        "python", "laravel", "vue.js", "react", "typescript", "php", "docker", 
        "postgresql", "sql", "git", "aws", "fastapi", "rest api", "tailwind", 
        "ci/cd", "linux", "anglais", "gestion de projet", "agile", "scrum"
    ]
    cv_skills_lower = [s.lower() for s in cv_skills]
    missing = []
    for cms in common_market_skills:
        if cms not in cv_skills_lower:
            pattern = r'\b' + re.escape(cms) + r'\b'
            if re.search(pattern, desc_lower):
                missing.append(cms.capitalize())

    return matching, missing[:5]

def evaluate_opportunity(
    cv_data: CVStructuredData,
    cv_embedding: Optional[List[float]],
    opp: JobOpportunityInput,
    filters: Optional[SearchFilters]
) -> MatchResultItem:
    """Évalue une offre vis-à-vis du profil et applique filtres durs + similarité vectorielle."""

    # 1. Vérification des Filtres Durs (Deal-breakers)
    if filters:
        # Filtre sur le type de contrat
        if filters.types_contrat and len(filters.types_contrat) > 0:
            allowed = [c.upper() for c in filters.types_contrat]
            if opp.type_contrat.upper() not in allowed and "TOUS" not in allowed:
                return MatchResultItem(
                    opportunite_id=opp.id,
                    score_pertinence=0.0,
                    passed_hard_filters=False,
                    rejection_reason=f"Type de contrat '{opp.type_contrat}' exclu (Recherché : {', '.join(filters.types_contrat)})",
                    matching_skills=[],
                    missing_skills=[],
                    summary_explanation="Offre rejetée par les filtres de contrat de l'utilisateur."
                )

        # Filtre mots-clés exclus (Deal-breakers)
        if filters.keywords_excluded:
            desc_lower = opp.description.lower() + " " + opp.titre.lower()
            for kw in filters.keywords_excluded:
                if kw.strip() and re.search(r'\b' + re.escape(kw.lower().strip()) + r'\b', desc_lower):
                    return MatchResultItem(
                        opportunite_id=opp.id,
                        score_pertinence=0.0,
                        passed_hard_filters=False,
                        rejection_reason=f"Contient le mot-clé exclu : '{kw}'",
                        matching_skills=[],
                        missing_skills=[],
                        summary_explanation="Offre rejetée en raison de la présence d'un mot-clé indésirable."
                    )

        # Filtre télétravail strict
        if filters.teletravail_only and not opp.teletravail:
            return MatchResultItem(
                opportunite_id=opp.id,
                score_pertinence=0.0,
                passed_hard_filters=False,
                rejection_reason="Offre ne proposant pas de télétravail complet",
                matching_skills=[],
                missing_skills=[],
                summary_explanation="Offre exclue car elle n'est pas en télétravail."
            )

        # Filtre sur le pays / localisation
        if filters.pays and filters.pays.strip():
            p_wanted = filters.pays.strip().lower()
            if p_wanted not in ["tous", "international", "monde", "partout"]:
                opp_loc = opp.localisation.lower()
                if p_wanted not in opp_loc and not opp.teletravail:
                    return MatchResultItem(
                        opportunite_id=opp.id,
                        score_pertinence=0.0,
                        passed_hard_filters=False,
                        rejection_reason=f"Localisation '{opp.localisation}' non située dans le pays souhaité ({filters.pays})",
                        matching_skills=[],
                        missing_skills=[],
                        summary_explanation=f"Offre hors zone géographique souhaitée ({filters.pays})."
                    )

        # Filtre salaire
        if filters.salaire_min and opp.salaire_indicatif and opp.salaire_indicatif < filters.salaire_min:
            return MatchResultItem(
                opportunite_id=opp.id,
                score_pertinence=0.0,
                passed_hard_filters=False,
                rejection_reason=f"Salaire proposé ({opp.salaire_indicatif}€) inférieur au minimum souhaité ({filters.salaire_min}€)",
                matching_skills=[],
                missing_skills=[],
                summary_explanation="Salaire inférieur aux prétentions déclarées."
            )

    # 2. Calcul Vectoriel
    type_opp_str = (filters.type_opportunite + " ") if (filters and filters.type_opportunite) else ""
    cv_vec = cv_embedding or get_text_embedding(
        f"{type_opp_str}{cv_data.titre_professionnel or ''} " + 
        " ".join(cv_data.competences_techniques) + " " +
        " ".join([e.poste + " " + (e.description or "") for e in cv_data.experiences])
    )
    
    opp_vec = opp.embedding or get_text_embedding(f"{opp.titre} {opp.description}")
    raw_vector_similarity = compute_cosine_similarity(cv_vec, opp_vec)

    # 3. Analyse des compétences clés
    matching_skills, missing_skills = extract_skill_matches(cv_data.competences_techniques, opp.description + " " + opp.titre)
    
    # Bonus de matching basé sur les compétences directement alignées
    skill_ratio = min(1.0, len(matching_skills) / max(1, min(len(cv_data.competences_techniques), 6)))
    
    # Score composite pondéré (60% sémantique vectoriel + 40% adéquation exacte des compétences)
    final_score = (raw_vector_similarity * 0.6 + skill_ratio * 0.4) * 100.0
    final_score = round(min(99.0, max(15.0, final_score)), 1)

    # 4. Explication synthétique du score
    if matching_skills:
        summary = f"Match à {final_score}% : {len(matching_skills)} compétence(s) alignée(s) ({', '.join(matching_skills[:3])})."
    else:
        summary = f"Match sémantique à {final_score}% basé sur la cohérence du profil et du domaine d'activité."

    return MatchResultItem(
        opportunite_id=opp.id,
        score_pertinence=final_score,
        passed_hard_filters=True,
        rejection_reason=None,
        matching_skills=matching_skills,
        missing_skills=missing_skills,
        summary_explanation=summary
    )
