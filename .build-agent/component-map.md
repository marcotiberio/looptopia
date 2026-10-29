# Component mapping — Looptopia

One-page site for the **LOOPTOPIA** Berlin circular-economy city festival (13.–15.11.2026).
Source: Figma `Uin24Sxmh57ariAasM7IoW`, frame `218:820` ("Website // Desktop // 1200px").
Pulled via Figma MCP. **Agent first pass — review & confirm at CHECKPOINT 1.**

## Design tokens (see `tailwind.tokens.js`)

- Colors: Black `#000000`, White `#ffffff`, **Bright Green `#c7f59a`** (accent).
- Font: **Poppins** (Medium 500 + SemiBold 600) — needs adding (Google Fonts / self-host).
- Type scale: H1 48 · H2 32 · H3 24 · Body XL 32 · Body 20 · Body S 16 · Button 20 (line-height 1).
- Recurring motifs: rounded "pill" buttons with circular arrow icon; light-green rounded callout
  boxes; decorative green outline blobs (background SVGs, not components).

## Section → component map (top to bottom)

| # | Design section / frame | Flynt component | Status | ACF fields / notes |
|---|---|---|---|---|
| — | Sticky nav bar (`218:3855` Menu) | `NavigationMain` | reuse | anchor links to page sections; logo |
| 1 | Header / hero (`218:890`) | `HeroImage` | **reuse (extend)** | kicker "BAU MIT UNS", date "13.–15.11.26", LOOPTOPIA wordmark (SVG), headline, scroll chevron, bg image, funder logos. Custom enough it may warrant **NEW `BlockHero`** — confirm |
| 2 | Intro paragraph + 2 CTAs (`218:889`) | `BlockWysiwyg` + `BlockButtons` | reuse | rich text; two pill buttons ("Programmidee einreichen", "LOOPTOPIA unterstützen") |
| 3 | Über Uns / about (`218:888`) | `BlockImageText` | reuse (extend) | heading, body, green callout box ("Das Ziel…"), project credit, image right, 1 button |
| 4a | Teilnehmen intro (`218:893`) | `BlockWysiwyg` | reuse | "TEILNEHMEN" heading + intro paragraph |
| 4b | 3 Themenfeld cards (`218:995`) | **NEW `BlockCards`** | **NEW** | 3-col cards: icon, number, title, description. No existing 3-card grid — propose new |
| 4c | "Was auf dich wartet" / "So wirst du Teil" (`218:1241`) | `BlockWysiwygColumns` | reuse | 2-col text, 1 button, "Teilnahmekriterien" link |
| 4d | SPECIAL + CALL FOR SPACES boxes (`218:1245`) | `BlockBannerCta` | reuse (×2) | green callout boxes: heading, text, link. Confirm vs NEW `BlockInfoBox` |
| 5 | Partner & Förderer (`218:960`) | **NEW `BlockPartnerLogos`** | **NEW** | logo grid grouped by tier (Förderer/Vorreiter/Gestalter/Unterstützer) + paragraph + button + Zero-Waste badge. `BlockSliderLogos` exists but is a slider; design is a static tiered grid |
| 6 | FAQs accordion (`218:973`) | `BlockAccordionDefault` | reuse | heading + 9 Q&A items (chevron-down toggles) |
| 7 | Kontakt (`218:990`) | `BlockWysiwyg` | reuse | heading + paragraph w/ email `looptopia@circular.berlin`, IG/newsletter; bg blob |
| 8 | Footer (`218:1087`) | `NavigationFooter` | reuse (extend) | 3 social columns (LinkedIn/Instagram/Bluesky), funder logos, big LOOPTOPIA wordmark |

### Summary
- **Reuse: ~9 sections** map onto existing boilerplate components (possibly with field/style extensions).
- **NEW: 2 confirmed** — `BlockCards` (3 theme cards), `BlockPartnerLogos` (tiered logo grid).
- **2 to confirm** — Hero (reuse `HeroImage` vs new `BlockHero`); SPECIAL/CALL boxes (reuse `BlockBannerCta` vs new `BlockInfoBox`).

### Pre-build setup (not components)
- Add **Poppins** font + merge `tailwind.tokens.js` into `tailwind.config.js → theme.extend`.
- LOOPTOPIA wordmark + circular-arrow icon + green blob shapes → export SVG assets from Figma.
- Page is a single ACF flexible-content page; register each component's `getACFLayout()` in `pageComponents`.

## Existing components available (47)

- BlockAccordionDefault
- BlockAnchor
- BlockBannerCta
- BlockButtons
- BlockCollapse
- BlockDivider
- BlockFeaturedArticle
- BlockFeaturedArticleTwo
- BlockGalleryMedia
- BlockImage
- BlockImageText
- BlockNotFound
- BlockPdfDownload
- BlockPostHeader
- BlockPostNav
- BlockSliderLogos
- BlockSpacer
- BlockVideoHeader
- BlockVideoOembed
- BlockVideoText
- BlockWysiwyg
- BlockWysiwygColumns
- FeatureFlexibleContentExtension
- GridImageText
- GridImages
- GridPostsArchive
- HeroImage
- ListComponents
- ListSearchResults
- ListingArticles
- ListingJournal
- ListingJournalRelated
- ListingProjects
- ListingProjectsFeat
- ListingVideo
- ListingVideoFeat
- ListingVideosRelated
- NavigationBurger
- NavigationFooter
- NavigationMain
- NavigationMainLeft
- NavigationMainRight
- RelatedPosts
- SliderBox
- SliderBoxText
- ZZZ
