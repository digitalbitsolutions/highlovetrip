# Deployment Workflow

Last updated: 2026-04-05

## Scope

This project currently uses a manual delivery flow, not a fully automated CI/CD pipeline.

Decision for now:

- stay on manual/semi-manual delivery
- do not implement or assume fully automated CI/CD yet

The practical source of truth is:

- local code in this repo
- local Docker WordPress runtime
- production WordPress at `https://highlovetrip.com`

The goal is to keep production and local `1:1`.

## Non-negotiable security rule

- The SSH private key is encrypted.
- The SSH key passphrase must never be stored in the repo, scripts, env files, notes, or committed history.
- The passphrase must always be requested from the human operator before any production SSH action.
- In this project, the human operator is either **Harold** (Owner/QA) or **Meeguel** (Dev).

## Environment facts

- Local Docker WordPress runtime is the staging point before production.
- Production connection details exist only in local, non-versioned project context.
- The SSH private key path exists only in local, non-versioned project context.
- Local containers:
  - `hlt_wordpress`
  - `hlt_wp_db`

## Important operational notes

- `git push` updates GitHub only. It does not deploy to production by itself.
- Production currently does not have `wp-cli` available in shell.
- For production WordPress data/config changes, use remote `php` plus `wp-load.php`.
- `wp-config.php` in the repo is the production mirror. Local runtime uses `wp-config-docker.php`.

## Standard workflow

Until this document is explicitly changed, this manual/semi-manual flow remains the active process.

1. Verify local state first.
2. Make and validate the change locally.
3. If code changed, commit and push to GitHub.
4. If production must match local, ask the human for the SSH key passphrase.
5. Connect to production with the encrypted key.
6. Apply only the required production change.
7. Verify both the stored WordPress value and the public page output.
8. Do not persist the passphrase anywhere after the operation.

## Local verification commands

Check local containers:

```powershell
docker compose ps
```

Read a local post meta value:

```powershell
docker compose run --rm wpcli post meta get 533 header_transparency
```

Verify local frontend HTML:

```powershell
$html = Invoke-WebRequest -Uri 'http://localhost:8088/amuletum/' -UseBasicParsing
$html.Content
```

## Production access pattern

Because the private key is encrypted and production has no `wp-cli`, the safest non-interactive approach used here is Python + `paramiko`.

High-level pattern:

1. Ask the human for the passphrase.
2. Load the local project SSH private key with that passphrase in `paramiko`.
3. Connect to the production SSH host and user from the local, non-versioned project context.
4. Run commands inside the production WordPress web root from the local, non-versioned project context.

Minimal connection test:

```python
import paramiko

pkey = paramiko.RSAKey.from_private_key_file(
    PROJECT_PRIVATE_KEY_PATH,
    password=PASSPHRASE_FROM_HUMAN,
)

client = paramiko.SSHClient()
client.set_missing_host_key_policy(paramiko.AutoAddPolicy())
client.connect(
    hostname=PRODUCTION_HOST,
    port=PRODUCTION_PORT,
    username=PRODUCTION_USER,
    pkey=pkey,
    timeout=20,
)
```

## Production WordPress meta updates

Read a production meta value:

```bash
cd /path/to/production-wordpress-root && \
php -r 'require "wp-load.php"; echo get_post_meta(533, "header_transparency", true);'
```

Update a production meta value:

```bash
cd /path/to/production-wordpress-root && \
php -r 'require "wp-load.php"; update_post_meta(533, "header_transparency", "header_transparent"); echo get_post_meta(533, "header_transparency", true);'
```

This pattern can be adapted for:

- `update_post_meta()`
- `update_option()`
- `wp_update_post()`
- other narrowly scoped WordPress changes

## Required verification after production changes

- Verify the underlying stored value on production.
- Verify the public frontend output from `https://highlovetrip.com`.
- Verify that local still matches production.

Example public verification:

```powershell
$html = Invoke-WebRequest -Uri 'https://highlovetrip.com/amuletum/' -UseBasicParsing
$html.Content
```

## Git workflow

When code changes are made:

```powershell
git status --short
git add <files>
git commit -m "<message>"
git push origin main
```

Remember:

- pushing to GitHub is not the same as deploying to production
- production parity must still be checked explicitly

## Current known production limitation

- Shell `wp` command is not installed or not available in production user PATH.
- Use `php -r 'require "wp-load.php"; ...'` for remote WordPress data/config operations until that changes.

## Public repo rule

- This repository is public.
- Only version the deployment process here.
- Do not version production hostnames, usernames, filesystem paths, private key paths, passphrases, or any other environment-specific access data here.
- Keep environment-specific connection details in local-only context files that are not committed.

## Session rule for future work

Before any future production SSH action:

1. Read this file.
2. Ask the human for the SSH passphrase.
3. Do not continue with production access until the human provides it.
