import os
import subprocess
from docx import Document
from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT, WD_ALIGN_VERTICAL
from docx.oxml import parse_xml, OxmlElement
from docx.oxml.ns import nsdecls, qn

OUTPUT_DIR = r"C:\Users\Past HOUNGBEDJI Léon\.gemini\antigravity\scratch\jobmatch-ai"
DOCX_PATH = os.path.join(OUTPUT_DIR, "JobMatch_AI_Cahier_des_charges_v1.1.docx")
HTML_PATH = os.path.join(OUTPUT_DIR, "JobMatch_AI_Cahier_des_charges_v1.1.html")
PDF_PATH = os.path.join(OUTPUT_DIR, "JobMatch_AI_Cahier_des_charges_v1.1.pdf")
EDGE_PATH = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"

os.makedirs(OUTPUT_DIR, exist_ok=True)

# -------------------------------------------------------------
# 1. GENERATE WORD (.DOCX)
# -------------------------------------------------------------
def set_cell_background(cell, fill_hex):
    shading_elm = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    cell._tc.get_or_add_tcPr().append(shading_elm)

def set_cell_margins(cell, top=100, bottom=100, left=150, right=150):
    tcPr = cell._tc.get_or_add_tcPr()
    tcMar = OxmlElement('w:tcMar')
    for m, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        node = OxmlElement(f'w:{m}')
        node.set(qn('w:w'), str(val))
        node.set(qn('w:type'), 'dxa')
        tcMar.append(node)
    tcPr.append(tcMar)

def create_styled_table(doc, headers, data, col_widths=None):
    table = doc.add_table(rows=len(data) + 1, cols=len(headers))
    table.alignment = WD_TABLE_ALIGNMENT.CENTER
    table.autofit = False

    # Header row
    hdr_cells = table.rows[0].cells
    for i, title in enumerate(headers):
        hdr_cells[i].text = title
        set_cell_background(hdr_cells[i], "1E3A8A") # Navy Blue
        p = hdr_cells[i].paragraphs[0]
        p.alignment = WD_ALIGN_PARAGRAPH.LEFT
        for run in p.runs:
            run.font.bold = True
            run.font.color.rgb = RGBColor(255, 255, 255)
            run.font.name = "Segoe UI"
            run.font.size = Pt(9.5)
        set_cell_margins(hdr_cells[i], top=120, bottom=120, left=160, right=160)

    # Data rows
    for r_idx, row_data in enumerate(data):
        row_cells = table.rows[r_idx + 1].cells
        bg_color = "F8FAFC" if r_idx % 2 == 1 else "FFFFFF"
        for c_idx, val in enumerate(row_data):
            row_cells[c_idx].text = str(val)
            set_cell_background(row_cells[c_idx], bg_color)
            p = row_cells[c_idx].paragraphs[0]
            for run in p.runs:
                run.font.name = "Segoe UI"
                run.font.size = Pt(9)
                run.font.color.rgb = RGBColor(51, 65, 85)
            set_cell_margins(row_cells[c_idx], top=100, bottom=100, left=150, right=150)

    # Apply col widths
    if col_widths:
        for row in table.rows:
            for idx, width in enumerate(col_widths):
                row.cells[idx].width = Inches(width)

    # Borders
    tblPr = table._tbl.tblPr
    tblBorders = parse_xml(
        f'<w:tblBorders {nsdecls("w")}>'
        '<w:top w:val="single" w:sz="4" w:space="0" w:color="CBD5E1"/>'
        '<w:bottom w:val="single" w:sz="6" w:space="0" w:color="94A3B8"/>'
        '<w:insideH w:val="single" w:sz="4" w:space="0" w:color="E2E8F0"/>'
        '<w:insideV w:val="none"/>'
        '<w:left w:val="none"/>'
        '<w:right w:val="none"/>'
        '</w:tblBorders>'
    )
    tblPr.append(tblBorders)
    doc.add_paragraph() # Spacing

def build_docx():
    doc = Document()
    
    # Page setup
    for section in doc.sections:
        section.top_margin = Inches(1)
        section.bottom_margin = Inches(1)
        section.left_margin = Inches(1)
        section.right_margin = Inches(1)
        
        # Header / Footer
        header = section.header
        hp = header.paragraphs[0]
        hp.text = "JobMatch AI — Cahier des charges (v1.1 Révisée)"
        hp.alignment = WD_ALIGN_PARAGRAPH.RIGHT
        if hp.runs:
            hp.runs[0].font.size = Pt(8.5)
            hp.runs[0].font.color.rgb = RGBColor(148, 163, 184)

    # --- COVER PAGE ---
    for _ in range(3):
        doc.add_paragraph()

    title_p = doc.add_paragraph()
    title_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    trun = title_p.add_run("CAHIER DES CHARGES")
    trun.font.name = "Segoe UI"
    trun.font.size = Pt(28)
    trun.font.bold = True
    trun.font.color.rgb = RGBColor(30, 58, 138) # Primary Navy

    subtitle_p = doc.add_paragraph()
    subtitle_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    srun = subtitle_p.add_run("JobMatch AI")
    srun.font.name = "Segoe UI"
    srun.font.size = Pt(22)
    srun.font.bold = True
    srun.font.color.rgb = RGBColor(37, 99, 235) # Royal Blue

    desc_p = doc.add_paragraph()
    desc_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    drun = desc_p.add_run("Plateforme de recherche d'opportunités et d'assistance à la candidature")
    drun.font.name = "Segoe UI"
    drun.font.size = Pt(13)
    drun.font.italic = True
    drun.font.color.rgb = RGBColor(100, 116, 139)

    for _ in range(6):
        doc.add_paragraph()

    meta_p = doc.add_paragraph()
    meta_p.alignment = WD_ALIGN_PARAGRAPH.CENTER
    mrun = meta_p.add_run("Version 1.1 (Révisée & Enrichie) — Septembre 2026\nPorteur de projet : Grégoire\nArchitecture : Laravel 11 + Vue 3 / Python FastAPI & pgvector")
    mrun.font.name = "Segoe UI"
    mrun.font.size = Pt(10.5)
    mrun.font.color.rgb = RGBColor(71, 85, 105)

    doc.add_page_break()

    # --- HELPER FORMATTING ---
    def add_h1(text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(16)
        p.paragraph_format.space_after = Pt(8)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.font.name = "Segoe UI"
        run.font.size = Pt(16)
        run.font.bold = True
        run.font.color.rgb = RGBColor(30, 58, 138)
        return p

    def add_h2(text):
        p = doc.add_paragraph()
        p.paragraph_format.space_before = Pt(12)
        p.paragraph_format.space_after = Pt(6)
        p.paragraph_format.keep_with_next = True
        run = p.add_run(text)
        run.font.name = "Segoe UI"
        run.font.size = Pt(13)
        run.font.bold = True
        run.font.color.rgb = RGBColor(37, 99, 235)
        return p

    def add_p(text, bold_prefix=None):
        p = doc.add_paragraph()
        p.paragraph_format.space_after = Pt(5)
        p.paragraph_format.line_spacing = 1.15
        if bold_prefix:
            br = p.add_run(bold_prefix)
            br.bold = True
            br.font.name = "Segoe UI"
            br.font.size = Pt(10)
            br.font.color.rgb = RGBColor(15, 23, 42)
        run = p.add_run(text)
        run.font.name = "Segoe UI"
        run.font.size = Pt(10)
        run.font.color.rgb = RGBColor(51, 65, 85)
        return p

    def add_bullet(text, bold_prefix=None):
        p = doc.add_paragraph(style='List Bullet')
        p.paragraph_format.space_after = Pt(4)
        p.paragraph_format.line_spacing = 1.15
        if bold_prefix:
            br = p.add_run(bold_prefix)
            br.bold = True
            br.font.name = "Segoe UI"
            br.font.size = Pt(10)
            br.font.color.rgb = RGBColor(15, 23, 42)
        run = p.add_run(text)
        run.font.name = "Segoe UI"
        run.font.size = Pt(10)
        run.font.color.rgb = RGBColor(51, 65, 85)
        return p

    # --- SECTION 1 ---
    add_h1("1. Contexte et objectifs")
    add_h2("1.1 Contexte")
    add_p("De nombreux candidats (chercheurs d'emploi, stagiaires, étudiants en quête de bourses, freelances) passent un temps considérable à parcourir manuellement de multiples plateformes pour trouver des opportunités correspondant à leur profil, puis à rédiger une candidature personnalisée pour chacune. Ce processus est chronophage, répétitif et souvent inefficace.")
    add_p("JobMatch AI vise à automatiser la découverte d'opportunités pertinentes et à assister l'utilisateur dans la préparation et la personnalisation de sa candidature, tout en maintenant un contrôle humain strict (human-in-the-loop) avant tout envoi.")

    add_h2("1.2 Objectifs du projet")
    add_bullet(" issues de sources hybrides (APIs ouvertes, flux d'offres et scraping ciblé) en une seule interface centralisée.", "Centraliser la veille d'opportunités :")
    add_bullet(" entre les profils utilisateurs et les offres collectées, basé sur la vectorisation sémantique et le filtrage multicritère.", "Matching intelligent :")
    add_bullet(" lettre de motivation sur-mesure et propositions de restructuration de CV adaptées à chaque annonce ciblée.", "Génération assistée à fort impact :")
    add_bullet(" email pré-rempli et assistant de complétion de formulaires web, avec validation humaine obligatoire (zéro spam).", "Faciliter la soumission :")
    add_bullet(" pour le suivi analytique du cycle de vie des candidatures (du brouillon à l'embauche).", "Tableau de bord complet :")

    add_h2("1.3 Public cible")
    add_bullet(" (candidat individuel pilotant son profil de recherche, ses filtres et ses envois).", "Phase 1 (MVP & V1) : Usage personnel")
    add_bullet(" (structures d'accompagnement à l'emploi, universités, cabinets de reclassement, coachs en insertion).", "Phase 2 (V2) : Multi-utilisateurs SaaS")

    # --- SECTION 2 ---
    add_h1("2. Périmètre fonctionnel")
    add_h2("2.1 Gestion du profil utilisateur")
    add_bullet("Création de compte et authentification sécurisée (gestion de session, réinitialisation de mot de passe).")
    add_bullet("Upload de CVs (PDF/DOCX) avec extraction automatique par IA des métadonnées structurées : compétences techniques et soft-skills, expériences professionnelles, diplômes, langues et réalisations clés.")
    add_bullet("Définition des objectifs de recherche : types de contrats (CDI, CDD, stage, alternance, freelance, bourse), secteurs d'activité, localisation géographique (incluant télétravail partiel/total), fourchette de rémunération minimale, mots-clés obligatoires et mots-clés rédhibitoires.")
    add_bullet("Conformité RGPD : modification à tout moment, export des données et droit à l'effacement définitif du compte et des CVs.")

    add_h2("2.2 Collecte des opportunités (Ingestion hybride)")
    add_bullet(" intégration des APIs publiques gratuites et stables (ex. API France Travail, Adzuna, Jobposting JSON-LD, flux RSS d'offres) évitant les risques de blocage IP.", "Priorité 1 — APIs ouvertes & flux de syndication :")
    add_bullet(" extracteurs dédiés (Scrapy / Playwright) pour les sites carrières d'entreprises clés, plateformes locales et ONG.", "Priorité 2 — Scraping ciblé :")
    add_bullet(" calcul d'un hash d'unicité (entreprise normalisée + intitulé + localisation + extrait description) pour éliminer les réplications entre exécutions successives.", "Dédoublonnage intelligent :")
    add_bullet(" ajout et paramétrage d'une nouvelle source (URL, sélecteurs, fréquence) via panneau d'administration sans redéploiement de code.", "Configuration dynamique :")

    add_h2("2.3 Matching et scoring sémantique")
    add_bullet("Génération d'embeddings vectoriels pour chaque description d'offre et profil candidat.")
    add_bullet("Calcul de similarité cosinus via l'extension pgvector pour obtenir un score de 0 à 100%.")
    add_bullet("Application de filtres durs d'exclusion (mots-clés rédhibitoires, localisation incompatible, contrat non désiré) éliminant l'offre avant affichage.")
    add_bullet("Présentation des offres triées par pertinence avec explication synthétique des critères validés.")

    add_h2("2.4 Génération assistée de la candidature")
    add_bullet("Génération d'une lettre de motivation sur-mesure exploitant l'intersection exacte entre le CV structuré et les exigences de l'offre (ton professionnel, valorisation d'exemples concrets).")
    add_bullet("Recommandation d'adaptation du CV : proposition de réagencement des compétences et expériences clés pour franchir les filtres ATS (Applicant Tracking Systems), au format Markdown ou PDF épuré.")
    add_bullet("Édition en direct : interface de relecture et modification obligatoire par le candidat avant toute validation.")

    add_h2("2.5 Envoi et suivi de la candidature")
    add_bullet(" (destinataire, objet, corps, CV et lettre en pièces jointes) envoyé via SMTP personnel ou client local mailto:.", "Mode Email : Préparation d'un email complet")
    add_bullet(" assistance au pré-remplissage via extension de navigateur ou panneau latéral avec copie en 1-clic pour contourner sans friction les CAPTCHA et systèmes de sécurité tiers.", "Mode Formulaire Web :")
    add_bullet(" suivi sous forme de tableau Kanban des statuts (Brouillon, Validée, Envoyée, Relancée, Entretien, Refusée, Acceptée).", "Historique et cycle de vie :")

    add_h2("2.6 Alertes et notifications")
    add_bullet("Alerte proactive lorsqu'une nouvelle offre collectée dépasse un seuil de pertinence prédéfini (ex. > 85%).")
    add_bullet("Rappels programmés pour les candidatures en brouillon non finalisées.")

    # --- SECTION 3 ---
    add_h1("3. Exigences non fonctionnelles")
    add_h2("3.1 Infrastructure et dimensionnement")
    add_p("Déploiement obligatoire sur un VPS Linux dédié (ou infrastructure conteneurisée Docker) et formellement proscrit sur hébergement mutualisé standard. Le serveur requiert 4 vCPU et 8 Go de RAM minimum afin d'allouer les ressources requises par Playwright (Chromium headless), PostgreSQL avec pgvector, Redis et les runtimes PHP/Python.")

    add_h2("3.2 Performance et asynchronisme")
    add_bullet("Aucun traitement lourd (scraping, requêtes LLM, calcul d'embeddings) n'est exécuté de manière synchrone dans la requête HTTP utilisateur.")
    add_bullet("Temps de calcul du matching vectoriel < 1 seconde grâce aux index vectoriels HNSW sous PostgreSQL.")

    add_h2("3.3 Sécurité, confidentialité et RGPD")
    add_bullet("Chiffrement des données sensibles au repos et en transit (TLS 1.3 / HTTPS).")
    add_bullet("Cloisonnement strict : accès aux CVs, profils et historiques strictement limité à leur propriétaire (user_id).")
    add_bullet("Aucune conservation d'identifiants ou mots de passe de plateformes tierces sur les serveurs.")
    add_bullet("Politique de purge automatique des données d'offres expirées depuis plus de 60 jours.")

    add_h2("3.4 Éthique et conformité")
    add_bullet("Respect systématique des fichiers robots.txt et mise en œuvre d'un rate-limiting raisonnable avec délais aléatoires pour préserver les serveurs cibles.")
    add_bullet("Règle absolue du 'Zero-Spam' : aucune candidature n'est transmise sans confirmation expresse de l'utilisateur.")

    # --- SECTION 4 ---
    add_h1("4. Architecture technique")
    add_h2("4.1 Répartition des responsabilités et gouvernance")
    add_p("L'application repose sur un modèle à deux services complémentaires avec une délimitation stricte pour éliminer tout risque de conflit de schéma sur la base de données :")

    role_headers = ["Composant", "Rôle & Responsabilités"]
    role_data = [
        ["Application Laravel 11 + Vue 3 (Inertia)", "Source unique de vérité métier : Authentification, gestion exclusive des migrations BDD, dashboard candidat, validation des candidatures, notifications."],
        ["Service Python (FastAPI + Workers)", "Moteur d'ingestion & IA : Scraping Scrapy/Playwright, parsing CVs, génération vectorielle, orchestration des appels LLM. Consomme les files Redis."],
        ["PostgreSQL 16 + pgvector", "Base de données relationnelle et vectorielle unifiée avec index HNSW pour recherche sémantique ultra-rapide."],
        ["Redis", "Broker de messages asynchrones (Laravel Queues & Python Celery/RQ) et cache haute performance."],
        ["Infrastructure VPS Docker", "Conteneurs Docker Compose isolés (web, worker-python, db-pgvector, redis) sur VPS Linux dédié."]
    ]
    create_styled_table(doc, role_headers, role_data, [2.2, 4.3])

    add_h2("4.2 Stratégie d'étagement des modèles d'IA")
    ai_headers = ["Tâche Métier", "Modèle Recommandé", "Rationale Technique"]
    ai_data = [
        ["Extraction structurée CV & Offres", "Claude 3.5 Haiku / Gemini 2.5 Flash", "Latence ultra-faible, parsing JSON déterministe, coût dérisoire par document."],
        ["Calcul des Embeddings (Matching)", "Voyage AI (voyage-3-lite) / text-embedding-3-small", "Spécialisé pour la recherche de similarité cosinus, format dense optimisé pour pgvector."],
        ["Génération Lettre de motivation", "Claude 3.5 Sonnet", "Qualité rédactionnelle supérieure, style naturel, fidélité exemplaire au profil sans hallucinations."]
    ]
    create_styled_table(doc, ai_headers, ai_data, [2.0, 2.0, 2.5])

    # --- SECTION 5 ---
    add_h1("5. Modèle de données (Synthèse des entités)")
    db_headers = ["Entité", "Description & Champs Clés"]
    db_data = [
        ["utilisateurs", "Comptes de la plateforme (id, email, password_hash, rôle, dates de création/connexion)."],
        ["cvs", "CVs téléversés (id, user_id, original_filename, parsed_data JSONB, embedding VECTOR, is_default)."],
        ["profils_recherche", "Objectifs déclarés (id, user_id, types_contrat, localisations, salaire_min, keywords_must, keywords_excluded)."],
        ["sources", "Connecteurs configurés (id, nom, type_source ['api'|'rss'|'scraper'], config_selectors JSONB, is_active)."],
        ["opportunites", "Offres collectées (id, source_id, titre, entreprise, localisation, description, deduplication_hash UNIQUE, embedding VECTOR, url)."],
        ["matches", "Scores calculés (id, user_id, opportunite_id, score_pertinence, details_matching JSONB, statut ['nouveau'|'favori'|'ignore'])."],
        ["candidatures", "Dossiers de candidature (id, match_id, user_id, lettre_motivation, cv_modifications JSONB, statut ['brouillon'|'envoyee'|'entretien'|'reponse'], mode_envoi)."]
    ]
    create_styled_table(doc, db_headers, db_data, [1.8, 4.7])

    # --- SECTION 6 ---
    add_h1("6. Gestion des risques et solutions retenues")
    risk_headers = ["Risque Identifié", "Niveau", "Solution Validée dans le Cahier des Charges"]
    risk_data = [
        ["Rupture de sélecteurs HTML", "Élevé", "Priorisation des APIs/flux RSS stables. Alertes automatiques en cas d'anomalie d'extraction sur les scrapers."],
        ["CAPTCHA & anti-bots formulaires", "Élevé", "Remplissage déporté sur le navigateur (extension ou assistant 1-clic) évitant tout blocage serveur."],
        ["CGU LinkedIn & blocages comptes", "Élevé", "Exclusion du chemin critique V1. Focus sur sites carrières directs et APIs ouvertes d'emploi."],
        ["Saturation mémoire (OOM) Chromium", "Moyen", "Recyclage régulier des contextes Playwright, limitation stricte de concurrence sur le VPS."],
        ["Génération de lettres stéréotypées", "Moyen", "Relecture humaine obligatoire. Prompts stricts interdisant les inventions d'expériences."]
    ]
    create_styled_table(doc, risk_headers, risk_data, [2.0, 0.9, 3.6])

    # --- SECTION 7 ---
    add_h1("7. Phasage et feuille de route")
    phase_headers = ["Phase", "Périmètre Fonctionnel", "Objectif Clé"]
    phase_data = [
        ["MVP (Mois 1 - 2)", "2-3 sources stables (API France Travail + flux RSS), upload CV, parsing rapide, matching pgvector, génération lettre Claude, envoi email.", "Valider le concept et la valeur ajoutée sur un usage individuel initial."],
        ["V1 (Mois 3 - 4)", "Assistant de formulaires web, tableau de bord Kanban complet, alertes & notifications, scrapers carrières ciblés.", "Produit complet pour un usage individuel régulier et intensif."],
        ["V2 (Mois 5+)", "Multi-tenancy, gestion de cohortes pour structures d'accompagnement à l'emploi (SaaS B2B), connecteurs étendus.", "Passage à l'échelle et commercialisation auprès d'organismes."]
    ]
    create_styled_table(doc, phase_headers, phase_data, [1.4, 3.3, 1.8])

    # --- SECTION 8 ---
    add_h1("8. Livrables attendus")
    add_bullet("Code source versionné (Git) pour l'application Laravel 11 / Vue 3 et le service Python FastAPI.")
    add_bullet("Fichier docker-compose.yml complet orchestrant l'ensemble de la pile technique (Web, Worker, PostgreSQL pgvector, Redis).")
    add_bullet("Schéma de base de données complet et migrations Laravel versionnées.")
    add_bullet("Documentation technique de paramétrage des connecteurs de collecte et des variables d'environnement.")
    add_bullet("Guide utilisateur pour la prise en main du tableau de bord et des candidatures assistées.")

    doc.save(DOCX_PATH)
    print(f"[OK] Word generated: {DOCX_PATH}")

# -------------------------------------------------------------
# 2. GENERATE HTML FOR PDF PRINTING
# -------------------------------------------------------------
def build_html():
    html_content = """<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>JobMatch AI — Cahier des charges</title>
<style>
    @page {
        size: A4;
        margin: 20mm 16mm 20mm 16mm;
        @bottom-right {
            content: counter(page) " / " counter(pages);
            font-size: 8.5pt;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #94a3b8;
        }
        @top-right {
            content: "JobMatch AI — Cahier des charges v1.1";
            font-size: 8pt;
            font-family: 'Segoe UI', Arial, sans-serif;
            color: #94a3b8;
        }
    }
    body {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #1e293b;
        font-size: 9.5pt;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }
    .cover-page {
        page-break-after: always;
        height: 90vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .cover-badge {
        display: inline-block;
        background: #e0e7ff;
        color: #3730a3;
        font-weight: 600;
        font-size: 9pt;
        padding: 4px 12px;
        border-radius: 999px;
        margin-bottom: 20px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .cover-title {
        font-size: 32pt;
        font-weight: 800;
        color: #1e3a8a;
        margin: 0 0 8px 0;
        letter-spacing: -0.02em;
    }
    .cover-app {
        font-size: 24pt;
        font-weight: 700;
        color: #2563eb;
        margin: 0 0 16px 0;
    }
    .cover-desc {
        font-size: 13pt;
        color: #64748b;
        font-style: italic;
        max-width: 500px;
        margin-bottom: 50px;
    }
    .cover-meta {
        border-top: 1px solid #e2e8f0;
        padding-top: 24px;
        font-size: 10pt;
        color: #475569;
        line-height: 1.6;
    }
    h1 {
        color: #1e3a8a;
        font-size: 16pt;
        font-weight: 700;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 5px;
        margin-top: 24px;
        margin-bottom: 12px;
        page-break-after: avoid;
    }
    h2 {
        color: #2563eb;
        font-size: 12pt;
        font-weight: 600;
        margin-top: 16px;
        margin-bottom: 6px;
        page-break-after: avoid;
    }
    p {
        margin: 0 0 8px 0;
        text-align: justify;
    }
    ul {
        margin: 0 0 12px 18px;
        padding: 0;
    }
    li {
        margin-bottom: 5px;
    }
    strong {
        color: #0f172a;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 12px 0 16px 0;
        font-size: 8.8pt;
        page-break-inside: avoid;
    }
    th {
        background-color: #1e3a8a;
        color: #ffffff;
        font-weight: 600;
        text-align: left;
        padding: 7px 10px;
        border: 1px solid #1e3a8a;
    }
    td {
        padding: 7px 10px;
        border: 1px solid #e2e8f0;
        vertical-align: top;
    }
    tr:nth-child(even) td {
        background-color: #f8fafc;
    }
    .highlight-box {
        background-color: #eff6ff;
        border-left: 4px solid #3b82f6;
        padding: 10px 14px;
        margin: 12px 0;
        border-radius: 0 6px 6px 0;
        font-size: 9pt;
    }
    .badge {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 7.5pt;
        font-weight: 600;
    }
    .badge-high { background: #fee2e2; color: #991b1b; }
    .badge-med { background: #fef3c7; color: #92400e; }
    .page-break {
        page-break-before: always;
    }
</style>
</head>
<body>

<div class="cover-page">
    <div class="cover-badge">Document de Référence Technique & Produit</div>
    <div class="cover-title">CAHIER DES CHARGES</div>
    <div class="cover-app">JobMatch AI</div>
    <div class="cover-desc">Plateforme de recherche d'opportunités et d'assistance à la candidature</div>
    <div class="cover-meta">
        <strong>Version :</strong> 1.1 (Révisée & Enrichie) &bull; <strong>Date :</strong> Septembre 2026<br>
        <strong>Porteur de projet :</strong> Grégoire<br>
        <strong>Architecture :</strong> Laravel 11 + Vue.js 3 / Python FastAPI & pgvector
    </div>
</div>

<h1>1. Contexte et objectifs</h1>
<h2>1.1 Contexte</h2>
<p>De nombreux candidats (chercheurs d'emploi, stagiaires, étudiants en quête de bourses, freelances) passent un temps considérable à parcourir manuellement de multiples plateformes pour trouver des opportunités correspondant à leur profil, puis à rédiger une candidature personnalisée pour chacune. Ce processus est chronophage, répétitif et souvent inefficace.</p>
<p><strong>JobMatch AI</strong> vise à automatiser la découverte d'opportunités pertinentes et à assister l'utilisateur dans la préparation et la personnalisation de sa candidature, tout en maintenant un contrôle humain strict (<em>human-in-the-loop</em>) avant tout envoi.</p>

<h2>1.2 Objectifs du projet</h2>
<ul>
    <li><strong>Centraliser la veille d'opportunités :</strong> regroupement d'offres (emploi, stage, bourse, freelance) issues de sources hybrides (APIs ouvertes, flux d'offres et scraping ciblé) en une seule interface.</li>
    <li><strong>Matching intelligent :</strong> mise en correspondance automatique et sémantique entre les profils candidats et les offres collectées (vectorisation et filtrage multicritère).</li>
    <li><strong>Génération assistée à fort impact :</strong> rédaction d'une lettre de motivation sur-mesure et proposition de restructuration de CV adaptée à chaque annonce ciblée.</li>
    <li><strong>Faciliter la soumission :</strong> préparation d'emails prêts à l'envoi et assistance de complétion de formulaires web avec validation humaine obligatoire.</li>
    <li><strong>Tableau de bord de suivi :</strong> gestion du cycle de vie des candidatures envoyées et analyse de leur statut.</li>
</ul>

<h2>1.3 Public cible</h2>
<ul>
    <li><strong>Phase 1 (MVP & V1) : Usage personnel</strong> (un candidat pilote son propre profil de recherche, ses filtres et ses candidatures).</li>
    <li><strong>Phase 2 (V2) : Ouverture multi-utilisateurs SaaS</strong> (structures d'accompagnement à l'emploi, universités, cabinets de reclassement, coachs en insertion).</li>
</ul>

<h1>2. Périmètre fonctionnel</h1>
<h2>2.1 Gestion du profil utilisateur</h2>
<ul>
    <li>Création de compte et authentification sécurisée (gestion de session, réinitialisation de mot de passe).</li>
    <li>Upload multi-formats du CV (PDF, DOCX) avec extraction automatique par IA des métadonnées structurées : compétences techniques et comportementales, expériences professionnelles, formations, langues et réalisations.</li>
    <li>Définition des critères de recherche : types de contrats (CDI, CDD, stage, alternance, freelance, bourse), secteurs d'activité, localisation géographique (incluant télétravail partiel/total), rémunération minimale, mots-clés obligatoires et mots-clés exclus.</li>
    <li>Conformité RGPD : export des données personnelles et droit à l'effacement définitif du compte et des CVs associés.</li>
</ul>

<h2>2.2 Collecte des opportunités (Ingestion hybride)</h2>
<ul>
    <li><strong>Priorité 1 — APIs ouvertes & flux de syndication :</strong> intégration prioritaire des APIs publiques gratuites et stables (ex. API France Travail, Adzuna, Jobposting JSON-LD, flux RSS d'offres) éliminant les risques de blocage IP.</li>
    <li><strong>Priorité 2 — Scraping ciblé :</strong> extracteurs dédiés (Scrapy / Playwright) pour les sites carrières d'entreprises clés, plateformes locales et ONG.</li>
    <li><strong>Dédoublonnage intelligent :</strong> calcul d'un hash d'unicité (entreprise normalisée + intitulé + localisation + extrait description) pour éliminer les réplications.</li>
    <li><strong>Configuration dynamique :</strong> ajout et paramétrage d'une nouvelle source (URL, sélecteurs, fréquence) via panneau d'administration sans redéploiement de code.</li>
</ul>

<h2>2.3 Matching et scoring sémantique</h2>
<ul>
    <li>Génération d'embeddings vectoriels pour chaque description d'offre et profil candidat.</li>
    <li>Calcul de similarité cosinus via l'extension PostgreSQL <code>pgvector</code> pour obtenir un score de 0 à 100%.</li>
    <li>Application de filtres durs d'exclusion (mots-clés rédhibitoires, localisation incompatible, contrat non désiré) éliminant l'offre avant affichage.</li>
    <li>Restitution claire sur le tableau de bord avec tri par score et mise en valeur des compétences communes.</li>
</ul>

<h2>2.4 Génération assistée de la candidature</h2>
<ul>
    <li>Génération d'une lettre de motivation sur-mesure exploitant l'intersection exacte entre le profil du candidat et les exigences du poste (ton professionnel, valorisation d'exemples concrets).</li>
    <li>Recommandation d'adaptation du CV : proposition de réagencement des compétences et expériences prioritaires pour franchir les filtres ATS (Applicant Tracking Systems), au format Markdown ou PDF épuré.</li>
    <li>Édition manuelle obligatoire : validation impérative par le candidat avant tout envoi.</li>
</ul>

<h2>2.5 Envoi et suivi de la candidature</h2>
<ul>
    <li><strong>Mode Email :</strong> génération automatique de l'objet, du corps et des pièces jointes (CV et lettre en PDF), envoi via SMTP personnel ou client mail local.</li>
    <li><strong>Mode Formulaire Web :</strong> assistance au pré-remplissage via extension de navigateur ou panneau d'aide au copier-coller 1-clic pour contourner sans friction les CAPTCHA et systèmes de sécurité tiers.</li>
    <li><strong>Historique et suivi :</strong> tableau de bord Kanban (Brouillon, Validée, Envoyée, Relancée, Entretien, Refusée, Acceptée).</li>
</ul>

<h2>2.6 Système d'alertes et de rappels</h2>
<ul>
    <li>Alerte proactive lorsqu'une nouvelle offre collectée dépasse un seuil de pertinence prédéfini (ex. > 85%).</li>
    <li>Rappels programmés pour les candidatures en brouillon non finalisées.</li>
</ul>

<div class="page-break"></div>

<h1>3. Exigences non fonctionnelles</h1>
<h2>3.1 Infrastructure et dimensionnement</h2>
<p>Déploiement obligatoire sur un <strong>VPS Linux dédié</strong> (ou environnement conteneurisé Docker) et formellement proscrit sur hébergement mutualisé standard. Le serveur requiert <strong>4 vCPU et 8 Go de RAM minimum</strong> afin d'allouer les ressources nécessaires à Playwright (Chromium headless), PostgreSQL avec <code>pgvector</code>, Redis et les runtimes PHP/Python.</p>

<h2>3.2 Performance et asynchronisme</h2>
<ul>
    <li>Aucun traitement lourd (scraping, requêtes LLM, calcul vectoriel) n'est exécuté de manière synchrone dans la requête HTTP utilisateur.</li>
    <li>Temps de calcul du matching vectoriel &lt; 1 seconde grâce aux index vectoriels HNSW sous PostgreSQL.</li>
</ul>

<h2>3.3 Sécurité, confidentialité et RGPD</h2>
<ul>
    <li>Chiffrement des données sensibles au repos et en transit (TLS 1.3 / HTTPS).</li>
    <li>Cloisonnement strict : accès aux CVs, profils et historiques strictement limité à leur propriétaire (<code>user_id</code>).</li>
    <li>Aucune conservation d'identifiants ou mots de passe de plateformes tierces sur les serveurs.</li>
    <li>Politique de purge automatique des données d'offres expirées depuis plus de 60 jours.</li>
</ul>

<h2>3.4 Éthique et conformité</h2>
<ul>
    <li>Respect systématique des fichiers <code>robots.txt</code> et mise en œuvre d'un <em>rate-limiting</em> raisonnable avec délais aléatoires pour préserver les serveurs cibles.</li>
    <li>Règle absolue du <em>Zero-Spam</em> : aucune candidature n'est transmise sans validation explicite de l'utilisateur.</li>
</ul>

<h1>4. Architecture technique</h1>
<h2>4.1 Répartition des responsabilités et gouvernance des données</h2>
<p>L'application repose sur un modèle à deux services complémentaires avec une délimitation stricte pour éliminer tout risque de conflit de schéma sur la base de données :</p>

<table>
    <thead>
        <tr>
            <th style="width: 32%;">Composant</th>
            <th>Rôle & Responsabilités</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Application Laravel 11 + Vue 3 (Inertia)</strong></td>
            <td><strong>Source unique de vérité métier :</strong> Authentification, gestion exclusive des migrations BDD, dashboard candidat, validation des candidatures, notifications.</td>
        </tr>
        <tr>
            <td><strong>Service Python (FastAPI + Workers)</strong></td>
            <td><strong>Moteur d'ingestion & IA :</strong> Scraping Scrapy/Playwright, parsing CVs, génération vectorielle, orchestration des appels LLM. Consomme les files Redis.</td>
        </tr>
        <tr>
            <td><strong>PostgreSQL 16 + pgvector</strong></td>
            <td>Base de données relationnelle et vectorielle unifiée avec index HNSW pour recherche sémantique ultra-rapide.</td>
        </tr>
        <tr>
            <td><strong>Redis</strong></td>
            <td>Broker de messages asynchrones (Laravel Queues & Python Celery/RQ) et cache haute performance.</td>
        </tr>
        <tr>
            <td><strong>Infrastructure VPS Docker</strong></td>
            <td>Conteneurs Docker Compose isolés (web, worker-python, db-pgvector, redis) sur VPS Linux dédié.</td>
        </tr>
    </tbody>
</table>

<h2>4.2 Stratégie d'étagement des modèles d'IA</h2>
<table>
    <thead>
        <tr>
            <th style="width: 28%;">Tâche Métier</th>
            <th style="width: 30%;">Modèle Recommandé</th>
            <th>Rationale Technique</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Extraction structurée CV & Offres</strong></td>
            <td>Claude 3.5 Haiku / Gemini 2.5 Flash</td>
            <td>Latence ultra-faible, parsing JSON déterministe, coût dérisoire par document.</td>
        </tr>
        <tr>
            <td><strong>Calcul des Embeddings (Matching)</strong></td>
            <td>Voyage AI (voyage-3-lite) / text-embedding-3-small</td>
            <td>Spécialisé pour la similarité sémantique, format dense optimisé pour pgvector.</td>
        </tr>
        <tr>
            <td><strong>Génération Lettre de motivation</strong></td>
            <td>Claude 3.5 Sonnet</td>
            <td>Qualité rédactionnelle supérieure, style naturel, fidélité exemplaire au profil sans hallucinations.</td>
        </tr>
    </tbody>
</table>

<div class="page-break"></div>

<h1>5. Modèle de données (Synthèse des entités)</h1>
<table>
    <thead>
        <tr>
            <th style="width: 25%;">Entité</th>
            <th>Description & Champs Clés</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>utilisateurs</code></td>
            <td>Comptes de la plateforme (id, email, password_hash, rôle, dates de création/connexion).</td>
        </tr>
        <tr>
            <td><code>cvs</code></td>
            <td>CVs téléversés (id, user_id, original_filename, parsed_data JSONB, embedding VECTOR, is_default).</td>
        </tr>
        <tr>
            <td><code>profils_recherche</code></td>
            <td>Objectifs déclarés (id, user_id, types_contrat, localisations, salaire_min, keywords_must, keywords_excluded).</td>
        </tr>
        <tr>
            <td><code>sources</code></td>
            <td>Connecteurs configurés (id, nom, type_source ['api'|'rss'|'scraper'], config_selectors JSONB, is_active).</td>
        </tr>
        <tr>
            <td><code>opportunites</code></td>
            <td>Offres collectées (id, source_id, titre, entreprise, localisation, description, deduplication_hash UNIQUE, embedding VECTOR, url).</td>
        </tr>
        <tr>
            <td><code>matches</code></td>
            <td>Scores calculés (id, user_id, opportunite_id, score_pertinence, details_matching JSONB, statut ['nouveau'|'favori'|'ignore']).</td>
        </tr>
        <tr>
            <td><code>candidatures</code></td>
            <td>Dossiers de candidature (id, match_id, user_id, lettre_motivation, cv_modifications JSONB, statut ['brouillon'|'envoyee'|'entretien'|'reponse'], mode_envoi).</td>
        </tr>
    </tbody>
</table>

<h1>6. Gestion des risques et solutions retenues</h1>
<table>
    <thead>
        <tr>
            <th style="width: 28%;">Risque Identifié</th>
            <th style="width: 14%;">Niveau</th>
            <th>Solution Validée dans le Cahier des Charges</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Rupture de sélecteurs HTML</strong></td>
            <td><span class="badge badge-high">ÉLEVÉ</span></td>
            <td>Priorisation des APIs/flux RSS stables. Alertes automatiques en cas d'anomalie d'extraction sur les scrapers.</td>
        </tr>
        <tr>
            <td><strong>CAPTCHA & anti-bots formulaires</strong></td>
            <td><span class="badge badge-high">ÉLEVÉ</span></td>
            <td>Remplissage déporté sur le navigateur (extension ou assistant 1-clic) évitant tout blocage serveur.</td>
        </tr>
        <tr>
            <td><strong>CGU LinkedIn & blocages comptes</strong></td>
            <td><span class="badge badge-high">ÉLEVÉ</span></td>
            <td>Exclusion du chemin critique V1. Focus sur sites carrières directs et APIs ouvertes d'emploi.</td>
        </tr>
        <tr>
            <td><strong>Saturation mémoire (OOM) Chromium</strong></td>
            <td><span class="badge badge-med">MOYEN</span></td>
            <td>Recyclage régulier des contextes Playwright, limitation stricte de concurrence sur le VPS.</td>
        </tr>
        <tr>
            <td><strong>Génération de lettres stéréotypées</strong></td>
            <td><span class="badge badge-med">MOYEN</span></td>
            <td>Relecture humaine obligatoire. Prompts stricts interdisant les inventions d'expériences.</td>
        </tr>
    </tbody>
</table>

<h1>7. Phasage et feuille de route</h1>
<table>
    <thead>
        <tr>
            <th style="width: 22%;">Phase</th>
            <th style="width: 48%;">Périmètre Fonctionnel</th>
            <th>Objectif Clé</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>MVP (Mois 1 - 2)</strong></td>
            <td>2-3 sources stables (API France Travail + flux RSS), upload CV, parsing rapide, matching pgvector, génération lettre Claude, envoi email.</td>
            <td>Valider le concept et la valeur ajoutée sur un usage individuel initial.</td>
        </tr>
        <tr>
            <td><strong>V1 (Mois 3 - 4)</strong></td>
            <td>Assistant de formulaires web, tableau de bord Kanban complet, alertes & notifications, scrapers carrières ciblés.</td>
            <td>Produit complet pour un usage individuel régulier et intensif.</td>
        </tr>
        <tr>
            <td><strong>V2 (Mois 5+)</strong></td>
            <td>Multi-tenancy, gestion de cohortes pour structures d'accompagnement à l'emploi (SaaS B2B), connecteurs étendus.</td>
            <td>Passage à l'échelle et commercialisation auprès d'organismes.</td>
        </tr>
    </tbody>
</table>

<h1>8. Livrables attendus</h1>
<ul>
    <li><strong>Code source versionné (Git) :</strong> dépôts pour l'application Laravel 11 / Vue 3 et le service Python FastAPI.</li>
    <li><strong>Infrastructure-as-Code :</strong> fichier <code>docker-compose.yml</code> complet orchestrant l'ensemble de la pile technique (Web, Worker, PostgreSQL pgvector, Redis).</li>
    <li><strong>Schéma de base de données :</strong> migrations Laravel versionnées et scripts de seeders.</li>
    <li><strong>Documentation technique :</strong> spécification des endpoints API, événements Redis et guide de paramétrage des connecteurs.</li>
    <li><strong>Guide utilisateur :</strong> guide pas-à-pas pour la configuration du profil, la relecture des candidatures et le suivi Kanban.</li>
</ul>

</body>
</html>
"""
    with open(HTML_PATH, "w", encoding="utf-8") as f:
        f.write(html_content)
    print(f"[OK] HTML generated: {HTML_PATH}")

def build_pdf():
    cmd = [
        EDGE_PATH,
        "--headless",
        "--disable-gpu",
        "--run-all-compositor-stages-before-draw",
        f"--print-to-pdf={PDF_PATH}",
        "--no-pdf-header-footer",
        HTML_PATH
    ]
    subprocess.run(cmd, check=True)
    print(f"[OK] PDF generated: {PDF_PATH}")

if __name__ == "__main__":
    build_docx()
    build_html()
    build_pdf()
