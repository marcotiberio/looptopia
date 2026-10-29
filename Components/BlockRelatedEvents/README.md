# Block Related Events

Closing row of a single event page: up to three related program entries plus the
"Programm entdecken" button.

Entries of the same "Art des Programmpunkts" come first and the row is topped up
with the most recent entries, so it is always full. Cards render through
`templates/Partials/_eventCard.twig` — the same teaser used across the program.

## Usage

```twig
{{ renderComponent('BlockRelatedEvents', { post: post }) }}
```

## Params

| Param | Description |
|---|---|
| `post` | Current event. Falls back to the post in the loop. |
| `title` | Headline above the row. |
| `buttonLabel` | Label of the trailing button. |
| `programLink` | Target of the button. Defaults to the "programm" page, or the event archive. |
