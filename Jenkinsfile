pipeline {
  agent { label 'ecs' }

  environment {
    S3_BUCKET = 'builds.moovly.com'
    S3_BUCKET_DIR_WITH_BUILD_NUMBER = "${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}-${BUILD_NUMBER}"
    S3_BUCKET_DIR_BRANCH = "${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
    BUILD_TAG_PARSED = "${env.BUILD_TAG.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
    SCANNER_HOME = tool 'SonarQube Scanner 2.8'
  }

  stages {
    stage('Build') {
      steps {
        sh 'docker run --rm -v ${WORKSPACE}:/app composer install --no-dev --optimize-autoloader --no-scripts'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app node:8-stretch npm install'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app node:8-stretch npm run production'
      }
    }

    stage('Package') {
      steps {
         sh 'aws s3 sync moovly-wordpress-plugin.zip s3://${S3_BUCKET}/wordpress-plugin/${S3_BUCKET_DIR_WITH_BUILD_NUMBER}/'
      }
    }

    stage('Register') {
      steps {
        script {
          currentBuild.description = "https://s3-eu-west-1.amazonaws.com/${S3_BUCKET}/player/${S3_BUCKET_DIR_WITH_BUILD_NUMBER}"
        }
      }
    }

    stage('SonarQube') {
      steps {
        withSonarQubeEnv('SonarQube') {
          sh "echo ${SCANNER_HOME}"
          sh "${SCANNER_HOME}/bin/sonar-scanner -Dsonar.projectKey=${CONTAINER_NAME} -Dsonar.sources=./src -Dsonar.projectVersion=${SENTRY_VERSION}"
        }
      }
    }
  }
}