Pasos a seguir para la config
1. clonar el repositoio
   '''git clone...'''
2. confi el archivo env
   '''cp .env .env.local'''
3. instkar la s dependiceis
   '''docker-compose up -d'''
   '''docker compose exec web bash'''
   '''composer install'''
4. ejecutar el proyect
5. cargar la base de datos
   '''mysql -u root -pdbrootpass -h mysql-rds spotify < spotify.sql'''