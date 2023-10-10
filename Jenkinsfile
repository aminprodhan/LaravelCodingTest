
// pipeline {
//     agent any

//     environment {
//         // Define the SSH credentials
//         SSH_USER = 'your-ssh-username'
//         SSH_PASS = credentials('your-ssh-password-id') // Define your secret text credential ID
//         SSH_HOST = 'remote-server.example.com'
//         SSH_PORT = '22'
//     }

//     stages {
//         stage('SSH to Remote Server') {
//             steps {
//                 script {
//                     def sshScript = """
//                         sshpass -p '$SSH_PASS' ssh -o StrictHostKeyChecking=no -p $SSH_PORT $SSH_USER@$SSH_HOST << 'ENDSSH'
//                         # Replace this with your desired command
//                         echo 'Hello, remote server!'
//                         # Add more commands if needed
//                         ENDSSH
//                     """
//                     sh(script: sshScript, returnStatus: true)
//                 }
//             }
//         }
//     }
// }


pipeline {
    agent any
    environment {
        REMOTE_SERVER = "purevat.com"
        REMOTE_PORT = 1157
        REMOTE_USERNAME = "purevat"
        REMOTE_PASSWORD = "Dw%#z~N0@WO]"
        REMOTE_REPO_URL = "https://cursorltd:glpat-yq6Bn2sH7zmKy2atXarf@gitlab.com/cursorltd/vuernd.git"
        LOCAL_REPO_DIR = "/home/purevat/hello.purevat.com"
        GIT_BRANCH = "attendance-system"
    }
    stages {
        stage('SSH to Remote Server44') {
            
            steps {
                script {
                    
                    // def sshScript = """
                    //     sshpass -p ${REMOTE_PASSWORD} ssh -o StrictHostKeyChecking=no -p ${REMOTE_PORT} ${REMOTE_USERNAME}@${REMOTE_SERVER} 'git clone ${REMOTE_REPO_URL} ${LOCAL_REPO_DIR} || (cd ${LOCAL_REPO_DIR} && git pull origin ${GIT_BRANCH}'
                    // """
                    // def exitCode = sh(script: sshScript, returnStatus: true)
                    // if (exitCode == 0) {
                    //     currentBuild.result = 'SUCCESS'
                    // } else {
                    //     currentBuild.result = 'FAILURE'
                    // }
                    
                    def sshScript = """
                        sshpass -p ${REMOTE_PASSWORD} ssh -o StrictHostKeyChecking=no -p ${REMOTE_PORT} ${REMOTE_USERNAME}@${REMOTE_SERVER} << 'EOF'
                        # Replace this with your desired command
                        
                        git clone -b ${GIT_BRANCH} ${REMOTE_REPO_URL} ${LOCAL_REPO_DIR} || (cd ${LOCAL_REPO_DIR} &&  git pull origin ${GIT_BRANCH})
                        
                        
                        # Add more commands if needed
                        
                    """
                    def exitCode =sh(script: sshScript, returnStatus: true)
                    if (exitCode == 0) {
                        currentBuild.result = 'SUCCESS'
                    } else {
                        currentBuild.result = 'FAILURE'
                    }
                }
            }
            
            // steps {
            //     sshagent(credentials: ['remote-login-12345']) {
            //         sh """
            //             sshpass -p "_J~1cexz{C%7" ssh -o StrictHostKeyChecking=no -p 1157 purevat@purevat.com << 'ENDSSH'
            //             # Replace this with your desired command
            //             echo "Hello, remote server!"
            //             ENDSSH
            //         """
            //     }
            // }
            // steps {
            //     script {
            //         // Define the SSH credentials
            //         def remoteServer = [:]
            //         remoteServer.name = 'RemoteServer'
            //         remoteServer.host = 'purevat.com'
            //         remoteServer.user = 'purevat'
            //         remoteServer.password = '_J~1cexz{C%7'
            //         remoteServer.port = 1157 // Specify the SSH port

            //         // Execute a command on the remote server
            //         sshScript(remoteServer: remoteServer, script: '''
            //             # Replace this with your desired command
            //             echo "Hello, remote server!"
            //         ''')
            //     }
            // }
        }
    }
}


