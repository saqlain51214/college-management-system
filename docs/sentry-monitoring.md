# Sentry Monitoring Setup

## Purpose

This guide explains how to enable Sentry error monitoring for the JDCA project without making it a hard dependency during Phase 1 deployment.

## Current Status

- Environment placeholders already exist in:
  - [.env.example](file:///d:/Dashboard-J/.env.example)
  - [.env.production.example](file:///d:/Dashboard-J/.env.production.example)
- Service placeholders already exist in:
  - [services.php](file:///d:/Dashboard-J/config/services.php)
- Published Sentry config now exists in:
  - [sentry.php](file:///d:/Dashboard-J/config/sentry.php)
- Sentry package is installed in `composer.json`.

The package is installed with privacy-safe defaults, but no real DSN is enabled until production env values are provided.

## When To Enable Sentry

Enable Sentry when:

- the production server is live
- SMTP and queue workers are already stable
- the team wants centralised exception tracking
- someone is assigned to monitor and triage alerts

## Install Command

Already completed in this project:

```bash
composer require sentry/sentry-laravel
php artisan vendor:publish --tag=sentry-config --force
```

## Required ENV

```env
SENTRY_LARAVEL_DSN=https://examplePublicKey@o0.ingest.sentry.io/0
SENTRY_TRACES_SAMPLE_RATE=0.1
SENTRY_SEND_DEFAULT_PII=false
```

## Recommended Phase 1 Values

- `SENTRY_TRACES_SAMPLE_RATE=0.1`
- `SENTRY_SEND_DEFAULT_PII=false`

These values reduce noise and avoid sending unnecessary personal data in Version 1.

## Privacy Recommendation

For Phase 1:

- keep `SENTRY_SEND_DEFAULT_PII=false`
- do not send student passwords or raw sensitive form contents
- avoid attaching excessive request payloads unless needed for debugging

## Deployment Steps

```bash
php artisan config:clear
php artisan config:cache
php artisan queue:restart
php artisan sentry:test
```

## Verification

After enabling Sentry:

1. Trigger a test exception in a safe staging environment.
2. Confirm the event appears in Sentry.
3. Confirm environment name is correct.
4. Confirm no sensitive student credentials appear in payloads.

## Recommended Alert Rules

- new unhandled exception in production
- repeated exception spikes
- queue worker exception bursts
- mail delivery related failures

## Recommendation

For this project, Sentry is now **installed and ready to activate**.  
It becomes fully active once `SENTRY_LARAVEL_DSN` is set in production.
