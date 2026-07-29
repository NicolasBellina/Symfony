# Lessons

## Symfony form buttons — `label: false` empties the button
- **Symptom observed**: `<button type="submit" id="post_save" name="post[save]"></button>` rendu vide.
- **Cause**: pour un `SubmitType`, le *label* est le texte affiché DANS le bouton. `{{ form_row(form.save, { label: false }) }}` supprime donc tout le contenu du bouton.
- **Fix**: `{{ form_row(form.save) }}` (laisser le label défini dans le FormType, ex. `'Publier'`).
- **Diagnostic lesson**: vérifier d'abord le HTML *réellement rendu* avant de partir sur une hypothèse (j'avais d'abord supposé un souci CSRF alors que le symptôme visible était un bouton vide). Regarder le symptôme concret en premier.

## `form_end(form, { render_rest: false })` supprime le token CSRF
- `render_rest: false` empêche `form_rest()` de rendre le champ caché `_token`. Sans lui, la soumission échoue la validation CSRF silencieusement (`isValid()` = false, pas de flash).
- Utiliser `{{ form_end(form) }}` : `form_rest` ne rend que les champs non déjà rendus (ici juste le token).
