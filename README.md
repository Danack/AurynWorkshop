# AurynWorkshop


Docker community edition Version 17.06.0-ce-mac18 (18433)


docker-compose up --build


docker exec -it aurynworkshop_web_1 sh



Moving to full dependency injection with Auryn.

You guys may have heard Dan Ackroyd mention the Auryn library for doing dependency injection, once or twice over the past few years. Dan's going to run a workshop that covers:

* Introducing the basics of how to use the Auryn DI library.

* Creating command line apps using Auryn.

* Integrating the Auryn library into the Slim framework, to easily create testable web applications.

Please note, this workshop is for intermediate to advanced programmers. e.g. You should be comfortable implementing interfaces in classes.

You will need to bring a laptop running `docker compose` e.g. Docker Community edition 17.12.0




docker exec -it aurynworkshop_web_1 php vendor/bin/phinx migrate
docker exec -it aurynworkshop_web_1 php vendor/bin/phinx seed:run
