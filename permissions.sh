#!/bin/sh

fichiers="index.php galerie admin __cms__/donnees"

chgrp -R www-data $fichiers
chmod 664 $fichiers
find $fichiers -type d -print0 | xargs -0 chmod 775
