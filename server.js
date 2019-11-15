const fs = require('fs');
const path = require('path');
const browserSync = require("browser-sync");
const sass = require('node-sass');
const { exec } = require("child_process");
const proxy = process.argv[2];
const port = process.argv[3];
// Sass configuration
const sass_source = 'assets/sass/';
const sass_glob = '/**/*.scss';
const sass_destination = 'assets/css/';

/**
 * Serveur local de développement
 *
 * Permettant le rechargement en direct et la compilation de Sass.
 * 
 * Installation :
 * ~~~
 * npm install
 * ~~~
 *
 * Les paramètres de repertoire à surveiller et numéro de port peuvent
 * être passés au démarrage de la manière suivante :
 * ~~~
 * npm start <proxy> <port>
 * ~~~
 * Par exemple :
 * ~~~
 * npm start http://guidoline.loc 8888
 * ~~~
 */

browserSync.init({
  proxy: proxy ? proxy : "127.0.0.1",
  port: port ? port : 3000,
  open: false,
  watch: true,
});
  
browserSync
  .create()
  .watch(path.join(sass_source, sass_glob), (event, file) => {

    if (event === 'change') {
      const filepath = path.dirname(file.replace(sass_source, ''));
      const basename = path.basename(file, path.extname(file));
      const file_destination = path.join(sass_destination, filepath);
      
      if ( ! fs.existsSync(file_destination))
      {
        fs.mkdir(file_destination, {recursive: true}, (err) => {
          if (err) throw err;
        });  
      }

      const destination_file = path.join(file_destination, basename) + '.css';

      sass.render({
        file: file,
        outFile: destination_file,
        sourceMap: true,
      }, (error, result) => {
        if( ! error){

          fs.writeFile(destination_file, result.css, (err) => {
            if ( ! err) {
              console.log("Compilation de " + file);
              browserSync.notify("Injection de " + file);
              throw err;
            }
            else
            {
              console.log("Erreur lors de la compilation : " + err);
              browserSync.notify("Erreur lors d'inejection' : " + err);
            }
          });

          browserSync.notify(result);

        } else {
          browserSync.notify(error);
        }
      })

      browserSync.reload('*.css');
      browserSync.notify("Modification de fichiers " + file);
    }
  });
