Um sass (scss) mit yii nutzen zu können muss folgendes installiert sein
Sass installation auch beschrieben auf :
https://blog.kulturbanause.de/2014/06/sass-ueber-die-kommandozeile-installieren/

1) in der web- und test -config den AssetConverter auf Sass zeigen lassen (absoluter Pfad zum ruby)
'converter' => [
                'class' => 'yii\web\AssetConverter',
                'commands' => [
                    'scss' => ['css', 'L:\Tools\Ruby26-x64\bin\sass {from} {to}']                    
                ],
            ], 

2) Ruby installieren
https://rubyinstaller.org/

3) Sass installieren
gem install sass 

4) Sass testen
sass -v
