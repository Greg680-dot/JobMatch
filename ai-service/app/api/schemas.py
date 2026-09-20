from typing import List, Optional, Dict, Any
from pydantic import BaseModel, Field

# --- PARSING CV ---
class ExperienceItem(BaseModel):
    poste: str
    entreprise: Optional[str] = None
    periode: Optional[str] = None
    description: Optional[str] = None

class FormationItem(BaseModel):
    diplome: str
    etablissement: Optional[str] = None
    annee: Optional[str] = None

class CVStructuredData(BaseModel):
    nom: Optional[str] = None
    email: Optional[str] = None
    telephone: Optional[str] = None
    titre_professionnel: Optional[str] = None
    annees_experience: Optional[int] = 0
    competences_techniques: List[str] = []
    competences_soft: List[str] = []
    experiences: List[ExperienceItem] = []
    formations: List[FormationItem] = []
    langues: List[str] = []

class CVParseResponse(BaseModel):
    success: bool
    data: CVStructuredData
    embedding: List[float] = []
    raw_text_length: int = 0

# --- MATCHING ---
class SearchFilters(BaseModel):
    types_contrat: Optional[List[str]] = None # CDI, CDD, Stage, Alternance, Freelance
    localisations: Optional[List[str]] = None
    pays: Optional[str] = None
    type_opportunite: Optional[str] = None
    salaire_min: Optional[float] = None
    keywords_must: Optional[List[str]] = None
    keywords_excluded: Optional[List[str]] = None
    teletravail_only: Optional[bool] = False

class JobOpportunityInput(BaseModel):
    id: str
    titre: str
    entreprise: str
    localisation: str
    type_contrat: str
    description: str
    salaire_indicatif: Optional[float] = None
    teletravail: Optional[bool] = False
    embedding: Optional[List[float]] = None

class MatchResultItem(BaseModel):
    opportunite_id: str
    score_pertinence: float # 0.0 - 100.0
    passed_hard_filters: bool
    rejection_reason: Optional[str] = None
    matching_skills: List[str] = []
    missing_skills: List[str] = []
    summary_explanation: str

class MatchingBatchRequest(BaseModel):
    cv_data: CVStructuredData
    cv_embedding: Optional[List[float]] = None
    filters: Optional[SearchFilters] = None
    opportunites: List[JobOpportunityInput]

class MatchingBatchResponse(BaseModel):
    success: bool
    results: List[MatchResultItem]

# --- GENERATION LETTRE & ADAPTATION CV ---
class LetterGenerateRequest(BaseModel):
    candidate_profile: CVStructuredData
    job_title: str
    company_name: str
    job_description: str
    matching_skills: Optional[List[str]] = []
    tone: Optional[str] = "Professionnel et percutant"

class LetterGenerateResponse(BaseModel):
    success: bool
    cover_letter: str
    object_email: str
    cv_adaptation_tips: List[str]
    suggested_skills_to_highlight: List[str]

# --- COLLECTE ---
class CollectRequest(BaseModel):
    source_type: str = "rss" # "rss" | "france_travail"
    target_url: Optional[str] = None
    category: Optional[str] = None
    max_results: Optional[int] = 20

class CollectedOffer(BaseModel):
    id: str
    titre: str
    entreprise: str
    localisation: str
    type_contrat: str
    description: str
    url_source: str
    date_publication: str
    deduplication_hash: str
    embedding: Optional[List[float]] = None

class CollectResponse(BaseModel):
    success: bool
    count: int
    offers: List[CollectedOffer]
