docker compose up -d

if [ -f .env ]; then
    export $(grep -v '^#' .env | xargs)
fi
PROJECT_VERSION=$(date +'%Y.%m.%d.%H%M%S')

docker run \
    --rm \
    --network=sonarqube-local-check_sonar_network \
    -v ${YOUR_REPO}:/usr/src \
    -v ${YOUR_CACHE_DIR}:/opt/sonar-scanner/.sonar/cache \
    -e SONAR_HOST_URL="http://${SONARQUBE_URL}" \
    -e SONAR_TOKEN="${SONAR_TOKEN}" \
    -e EXTRA_ARGS="-Dsonar.projectKey=${PROJECT_KEY} \
    -Dsonar.qualitygate.wait=true \
    -Dsonar.exclusions=app/code/Hyva/CmsTailwindJit/view/adminhtml/web/*,dev/** \
    -Dsonar.inclusions=app/code/**,app/design/** \
    -DSonar.projectVersion=${PROJECT_VERSION} "\
    sonarsource/sonarqube-scan
