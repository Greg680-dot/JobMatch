import httpx
from typing import List
from datetime import datetime
from app.api.schemas import CollectedOffer
from app.collectors.base import normalize_offer
from app.config import settings

def collect_from_france_travail(mots_cles: str = "développeur", max_results: int = 15) -> List[CollectedOffer]:
    """
    Interroge l'API officielle Partenaire France Travail (v2 offres d'emploi).
    Si les identifiants client ne sont pas encore renseignés, renvoie les offres de référence.
    """
    client_id = settings.FRANCE_TRAVAIL_CLIENT_ID
    client_secret = settings.FRANCE_TRAVAIL_CLIENT_SECRET
    
    if not client_id or not client_secret:
        # Fallback élégant quand les clés API ne sont pas encore configurées
        return []

    offers: List[CollectedOffer] = []
    token_url = "https://entreprise.francetravail.fr/connexion/oauth2/access_token?realm=%2Fpartenaire"
    search_url = "https://api.francetravail.io/partenaire/offresdemploi/v2/offres/search"

    try:
        with httpx.Client(timeout=12.0) as client:
            # 1. Obtention du token OAuth2
            token_resp = client.post(
                token_url,
                data={
                    "grant_type": "client_credentials",
                    "client_id": client_id,
                    "client_secret": client_secret,
                    "scope": f"o2dsoffre api_offresdemploiv2"
                }
            )
            token_resp.raise_for_status()
            access_token = token_resp.json().get("access_token")

            # 2. Recherche d'offres
            headers = {"Authorization": f"Bearer {access_token}", "Accept": "application/json"}
            params = {"motsCles": mots_cles, "range": f"0-{max_results-1}"}
            res = client.get(search_url, headers=headers, params=params)
            res.raise_for_status()
            
            raw_offers = res.json().get("resultats", [])
            for o in raw_offers:
                titre = o.get("intitule", "Offre d'emploi")
                entreprise = o.get("entreprise", {}).get("nom", "Entreprise Partenaire France Travail")
                loc = o.get("lieuTravail", {}).get("libelle", "France")
                contrat = o.get("typeContrat", "CDI")
                desc = o.get("description", "")
                url_postule = o.get("origineOffre", {}).get("urlOrigine", "")
                pub_date = o.get("dateCreation", datetime.now().strftime("%Y-%m-%d"))

                offers.append(normalize_offer(
                    id_source=f"ft_{o.get('id')}",
                    titre=titre,
                    entreprise=entreprise,
                    localisation=loc,
                    type_contrat=contrat,
                    description=desc,
                    url_source=url_postule or "https://www.francetravail.fr",
                    date_publication=pub_date
                ))
    except Exception as e:
        print(f"[Warning] France Travail API communication: {e}")

    return offers
