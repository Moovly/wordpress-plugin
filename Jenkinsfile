pipeline {
  agent { label 'ecs' }

  environment {
    CONTAINER_NAME = "moovly-wordpress-plugin"
    CONTAINER_URL = "016352445818.dkr.ecr.eu-west-1.amazonaws.com/${CONTAINER_NAME}"
    CONTAINER_URL_NUMBER = "${CONTAINER_URL}:${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}-${BUILD_NUMBER}"
    CONTAINER_URL_BRANCH = "${CONTAINER_URL}:${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
    S3_BUCKET = 'builds.moovly.com'
    S3_BUCKET_DIR_WITH_BUILD_NUMBER = "${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}-${BUILD_NUMBER}"
    S3_BUCKET_DIR_BRANCH = "${BRANCH_NAME.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
    BUILD_TAG_PARSED = "${env.BUILD_TAG.replaceAll('[^a-zA-Z0-9-]', '').toLowerCase()}"
  }

  stages {
    stage('Credentials/Meta') {
      steps {
        sh '$(aws ecr get-login --no-include-email --region eu-west-1)'
      }
    }

    stage('Build') {
      steps {
        sh 'sed -i "s@{{build}}@${BUILD_NUMBER}@" moovly.php'
        sh 'docker run --rm -v ${WORKSPACE}:/app composer install --no-dev --optimize-autoloader --no-scripts --prefer-dist'
        sh 'find . -type d | grep .git | xargs rm -rf'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app node:8-stretch npm install'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app node:8-stretch npm run production'
        sh 'docker run --rm --workdir=/app -v ${WORKSPACE}:/app samepagelabs/zip zip -r moovly dist/* src/* vendor/* moovly.php README.md readme.txt package.json package-lock.json'
        sh 'aws s3 cp ./moovly.zip s3://${S3_BUCKET}/wordpress-plugin/latest/'
        sh 'cp ./moovly.zip ./moovly-1.0.${BUILD_NUMBER}'
        sh 'aws s3 cp ./moovly-1.0.${BUILD_NUMBER} s3://${S3_BUCKET}/wordpress-plugin/'
        sh 'export VERSION=1.0.${BUILD_NUMBER}'

        withCredentials([string(credentialsId: 'wordpress_username', variable: 'SVN_USERNAME'), string(credentialsId: 'wordpress_password', variable: 'SVN_PASSWORD')]) {
            sh './bin/svn.sh'
        }
      }

      post {
        always {
          archive 'moovly*'
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
