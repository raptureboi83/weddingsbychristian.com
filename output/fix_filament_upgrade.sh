#!/usr/bin/env bash
set -euo pipefail

# 1) Fix navigationGroup property types in all Filament PHP files.
find app/Filament -type f -name '*.php' -print0 | while IFS= read -r -d '' file; do
  perl -0pi -e 's/protected static \?string \$navigationGroup\s*=\s*/protected static string|\\UnitEnum|null \$navigationGroup = /g' "$file"
done

# 2) Fix PackageSections namespace/import typo: PackagesSections -> PackageSections.
if [ -d app/Filament/Resources/PackageSections ]; then
  find app/Filament/Resources/PackageSections -type f -name '*.php' -print0 | while IFS= read -r -d '' file; do
    perl -0pi -e 's/App\\Filament\\Resources\\PackagesSections/App\\Filament\\Resources\\PackageSections/g' "$file"
  done
fi

# 3) Fix class names to match filenames for PSR-4.
if [ -f app/Filament/Resources/PackageSections/Tables/PackagesSectionsTable.php ]; then
  perl -0pi -e 's/class\s+PackageSectionsTable\b/class PackagesSectionsTable/g' app/Filament/Resources/PackageSections/Tables/PackagesSectionsTable.php
fi

if [ -f app/Filament/Resources/PackageSections/Pages/ListPackagesSections.php ]; then
  perl -0pi -e 's/class\s+ListPackageSections\b/class ListPackagesSections/g' app/Filament/Resources/PackageSections/Pages/ListPackagesSections.php
fi

echo
echo 'Remaining ?string navigationGroup declarations:'
grep -RFn 'protected static ?string $navigationGroup' app/Filament || true

echo
echo 'Remaining PackagesSections references:'
grep -RFn 'PackagesSections' app/Filament || true

echo
echo 'PackageSections class declarations:'
grep -RFn '^class ' app/Filament/Resources/PackageSections || true

echo
echo 'Done. Next run:'
echo '  composer dump-autoload'
echo '  php artisan package:discover --ansi'
