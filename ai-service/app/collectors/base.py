import re
import hashlib
from typing import List
from app.api.schemas import CollectedOffer
from app.matching.engine import get_text_embedding

def generate_dedup_hash(titre: str, entreprise: str, localisation: str, desc: str) -> str:
    """Génère une empreinte unique MD5 pour éliminer les doublons d'offres."""
    norm_str = f"{titre.lower().strip()}_{entreprise.lower().strip()}_{localisation.lower().strip()}_{desc[:120].lower().strip()}"
    norm_str = re.sub(r'\s+', ' ', norm_str)
    return hashlib.md5(norm_str.encode('utf-8')).hexdigest()

def normalize_offer(
    id_source: str,
    titre: str,
    entreprise: str,
    localisation: str,
    type_contrat: str,
    description: str,
    url_source: str,
    date_publication: str
) -> CollectedOffer:
    """Standardise et vectorise une opportunité d'emploi brute."""
    dedup_hash = generate_dedup_hash(titre, entreprise, localisation, description)
    embedding = get_text_embedding(f"{titre} {description}")
    
    return CollectedOffer(
        id=id_source,
        titre=titre.strip(),
        entreprise=entreprise.strip() or "Entreprise Confidentielle",
        localisation=localisation.strip() or "France / Télétravail",
        type_contrat=type_contrat.strip().upper() or "CDI",
        description=description.strip(),
        url_source=url_source.strip(),
        date_publication=date_publication,
        deduplication_hash=dedup_hash,
        embedding=embedding
    )
