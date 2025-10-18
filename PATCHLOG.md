# PATCHLOG

> Adoption of standardized patch/comment conventions for the SocialSuite project.

## 2025-10-18 – Adopt GPT Patch Convention
- Add documentation for patch/commenting/tag rules.
- Add helper script to verify tag usage in codebase.
- Establish `patches/` folder for unified diff files.
- Tag format: `[SOCIALSUITE][GPT][YYYY-MM-DD HH:MM +07]`

### How to use
1. For every code change, add a brief tag comment near the change:
   ```php
   // [SOCIALSUITE][GPT][2025-10-18 08:13 +07] FIX: short description
   // [SOCIALSUITE][GPT] WHY: reason
   // [SOCIALSUITE][GPT] ROLLBACK: how to revert (if applicable)
   ```
2. Commit a unified diff to `patches/YYYY-MM-DD_short-description.patch`.
3. Update this `PATCHLOG.md` with a concise summary, test steps, rollback notes.

### Verify
Run `php scripts/verify_gpt_tags.php` to list files/lines that include the tag.