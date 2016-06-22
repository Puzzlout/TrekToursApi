#$1 dev or prod
mkdir ../deploy
cd ../deploy
git clone https://github.com/Puzzlout/CloudDeploy.git
chmod +x ~/deploy/CloudDeploy/deploy.sh
chmod +x ~/deploy/CloudDeploy/Projects/TrekTours/Api/install.sh
chmod +x ~/deploy/CloudDeploy/Projects/TrekTours/Api/refresh.sh
sh ~/deploy/CloudDeploy/deploy.sh
cd ../workspace
sh ~/deploy/CloudDeploy/Projects/TrekTours/Api/install.sh $1
sh ~/deploy/CloudDeploy/Projects/TrekTours/Api/refresh.sh $1
