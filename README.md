# Perfex LIMS

Laboratory Information Management System module for Perfex CRM. The module manages
subjects, laboratory orders, samples, analyses, panels, cultures, appointments,
results, reports, contracts, billing links, labels, and partner synchronization.

## Requirements

- Perfex CRM `3.4.x` (as declared by the module header).
- A PHP version supported by the installed Perfex CRM release.
- MySQL/MariaDB through the Perfex/CodeIgniter database layer.
- PHP cURL when partner synchronization is enabled.

## Installation and updates

1. Copy this directory to `modules/lims` in the Perfex CRM installation.
2. In Perfex, open **Setup → Modules** and activate **LIMS Module**.
3. Activation runs `install.php` and the module migrations automatically.
4. After replacing module files with a newer release, use the normal Perfex module
   update flow so pending migrations are applied.

Back up the application files and database before installing or updating the
module. Do not run individual migration files manually on a production database.

## Permissions

The module registers the following staff capabilities under `lims`:

- View
- Manage Orders
- Manage Samples
- Enter Results
- Verify Results
- Approve / Sign
- Quality Control
- Billing
- Appointments
- LIMS Admin

Assign only the capabilities required by each role. Destructive Subject actions
(delete, transfer, and archive) require **Manage Orders** or **LIMS Admin**.

## Main admin areas

- **LIMS → Subjects**: subject records and related history.
- **LIMS → Orders**: laboratory order creation and processing.
- **LIMS → Contracts**: client pricing contracts.
- **LIMS → Appointments**: collection appointments.
- **LIMS → Tests / Samples**: test queue and sample management.
- **Setup → LIMS**: sample types, analyses, panels, departments, cultures,
  statuses, partners, and reporting options.
- **Customer profile → Subjects / Contracts**: client-specific records.

## Partner synchronization

Partner synchronization uses an outbox/inbox workflow and supports the events
`order.created`, `samples.status`, and `results.saved`.

Configure each partner with its API URL, API key, API secret, and sync status.
Inbound API requests use these headers:

- `X-LIMS-API-KEY`
- `X-LIMS-EVENT`
- `X-LIMS-IDEMPOTENCY-KEY`
- `X-LIMS-SIGNATURE` when request signing is configured

The outbox endpoint is:

```text
/lims/cron/sync_outbox?key=PERFEX_CRON_KEY&limit=10
```

Schedule it using the same protected cron key configured in Perfex. Confirm that
the sync inbox/outbox tables exist after activation before enabling a partner.

## Development checks

Run PHP syntax validation from the module directory:

```bash
find . -type f -name '*.php' -not -path './.git/*' -print0 \
  | sort -z \
  | xargs -0 -n1 php -l
```

For inline JavaScript in PHP views, render or extract the final script and validate
it with `node --check`. PHP lint alone cannot detect JavaScript syntax errors.

This repository currently contains no Python (`.py`) source files.

## Troubleshooting

### A modal action redirects instead of opening

1. Check the browser console for JavaScript syntax/runtime errors.
2. Verify that jQuery and Bootstrap's modal plugin are loaded.
3. Confirm that the dependency endpoint returns JSON and does not redirect to a
   login or access-denied page.
4. Clear Perfex/application caches and force-refresh browser assets after updating
   module views.

### Partner events remain pending

1. Verify the Perfex cron key and the scheduled outbox URL.
2. Confirm the partner is active and synchronization is enabled.
3. Review the outbox `last_error`, attempt count, and next retry time.
4. Verify outbound HTTPS connectivity and the remote API credentials.
