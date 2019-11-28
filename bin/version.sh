#! /bin/bash
# Ce script doit être exécutable :
# $chmod +x ./version.sh
# echo "$(git rev-parse --show-toplevel)"/.version
root_path=$(git rev-parse --show-toplevel)
version=$(head -n 1 "$root_path/.version")
# Nom du dernier tag
last_version=$(git describe --abbrev=0 --tags)
# Ajouter le nouveau tag si celui ci est différent (supérieur) au précedent
if [ "$version" != "" ] && [ "$version" != "$last_version" ]; then
  # Ajout du dernier message de commit en commentaire de tag
  git tag -a "$version" -m $("git log -1 --format=%s")
  echo "Ajout du tag $version"
fi

# À ajouter à `.git/hooks/post-commit
# set_version="$(git rev-parse --show-toplevel)/version.sh"
# bash set_version
