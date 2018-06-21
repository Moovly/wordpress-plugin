pipeline {
  agent { label 'ecs' }

  environment {
    CONTAINER_NAME = "api-user-service"
    CONTAINER_URL = "016352445818.dkr.ecr.eu-west-1.amazonaws.com/${CONTAINER_NAME}"
    CONTAINER_URL_NUMBER = "${CONTAINER_URL}:${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}-${BUILD_NUMBER}"
    CONTAINER_URL_BRANCH = "${CONTAINER_URL}:${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
    S3_BUCKET = 'builds.moovly.com'
    S3_BUCKET_DIR_WITH_BUILD_NUMBER = "${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}-${BUILD_NUMBER}"
    S3_BUCKET_DIR_BRANCH = "${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
    BUILD_TAG_PARSED = "${env.BUILD_TAG.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
    SCANNER_HOME = tool 'SonarQube Scanner 2.8'
  }

  stages {
    stage('Credentials/Meta') {
      steps {
        sh '$(aws ecr get-login --no-include-email --region eu-west-1)'
      }
    }

    stage('Build') {
      steps {
        sh 'docker run --rm -v ${WORKSPACE}:/app composer install --no-dev --optimize-autoloader --no-scripts'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app node:8-stretch npm install'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app node:8-stretch npm run production'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app samepagelabs/zip zip -r moovly-wordpress-plugin dist/* src/* vendor/* moovly.php package.json package-lock.json'
        sh 'ls -al'
      }

      post {
        always {
          archive 'moovly-wordpress-plugin*'
        }
      }
    }

    stage('Package') {
      steps {
         sh 'docker build -t ${BUILD_TAG_PARSED} .'
      }
    }

    stage('Register') {
      steps {
        sh 'docker tag ${BUILD_TAG_PARSED} ${CONTAINER_URL_NUMBER} && docker push ${CONTAINER_URL_NUMBER}'
        sh 'docker tag ${BUILD_TAG_PARSED} ${CONTAINER_URL_BRANCH} && docker push ${CONTAINER_URL_BRANCH}'

        script {
            currentBuild.description = "${CONTAINER_URL_NUMBER} | ${CONTAINER_URL_BRANCH}"
        }
      }
    }
  }
}