#!/bin/sh

fichiers="index.php galerie forum nouveautes equipes liens contact admin __cms__/donnees"

chgrp -R www-data $fichiers
chmod -R 664 $fichiers
find $fichiers -type d -print0 | xargs -0 chmod 775
