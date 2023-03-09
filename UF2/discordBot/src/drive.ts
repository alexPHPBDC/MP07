const fs = require('fs').promises;
const path = require('path');
import process from 'process';
import { authenticate } from '@google-cloud/local-auth';
import { google, drive_v3 } from 'googleapis';
import { OAuth2Client } from 'google-auth-library';
import { Message } from 'discord.js';

// If modifying these scopes, delete token.json.
// const SCOPES = ['https://www.googleapis.com/auth/drive.metadata.readonly'];
const SCOPES = [
    'https://www.googleapis.com/auth/drive',
    'https://www.googleapis.com/auth/drive.file',
    'https://www.googleapis.com/auth/drive.metadata',
    'https://www.googleapis.com/auth/drive.metadata.readonly',
    'https://www.googleapis.com/auth/drive.photos.readonly',
    'https://www.googleapis.com/auth/drive.readonly',
    'https://www.googleapis.com/auth/drive.scripts'
];

// The file token.json stores the user's access and refresh tokens, and is
// created automatically when the authorization flow completes for the first
// time.
const TOKEN_PATH = path.join(process.cwd(), 'token.json');
const CREDENTIALS_PATH = path.join(process.cwd(), 'credentials.json');

/**
 * Reads previously authorized credentials from the save file.
 *
 * @return {Promise<OAuth2Client|null>}
 */
async function loadSavedCredentialsIfExist(): Promise<OAuth2Client | null> {
    try {
        const content = await fs.readFile(TOKEN_PATH);
        const credentials = JSON.parse(content);
        return google.auth.fromJSON(credentials) as OAuth2Client;
    } catch (err) {
        return null;
    }
}

/**
 * Serializes credentials to a file comptible with GoogleAUth.fromJSON.
 *
 * @param {OAuth2Client} client
 * @return {Promise<void>}
 */
async function saveCredentials(client: any) {
    const content = await fs.readFile(CREDENTIALS_PATH);
    const keys = JSON.parse(content);
    const key = keys.installed || keys.web;
    const payload = JSON.stringify({
        type: 'authorized_user',
        client_id: key.client_id,
        client_secret: key.client_secret,
        refresh_token: client.credentials.refresh_token,
    });
    await fs.writeFile(TOKEN_PATH, payload);
}

/**
 * Load or request or authorization to call APIs.
 *
 */
export async function authorize() {
    let client = await loadSavedCredentialsIfExist();
    if (client) {
        return client;
    }
    client = await authenticate({
        scopes: SCOPES,
        keyfilePath: CREDENTIALS_PATH,
    });
    if (client.credentials) {
        await saveCredentials(client);
    }
    return client;
}

/**
 * Lists the names and IDs of up to 10 files.
 * @param {OAuth2Client} authClient An authorized OAuth2 client.
 */
export async function listFiles(authClient: OAuth2Client) {
    const drive = google.drive({ version: 'v3', auth: authClient });
    const res = await drive.files.list({
        pageSize: 6,
        fields: 'nextPageToken, files(id, name)',
    });
    const files = res.data.files;
    if (files!.length === 0) {
        console.log('No files found.');
        return;
    }

    console.log('Files:');
    files!.map((file: drive_v3.Schema$File) => {
        console.log(`${file.name} (${file.id})`);
    });
}

export async function createBotFolder(authClient: OAuth2Client, folderName: string) {
    const drive = google.drive({ version: 'v3', auth: authClient });
    const folderMetadata: drive_v3.Schema$File = {
        name: folderName,
        mimeType: 'application/vnd.google-apps.folder',
    };
    try {
        const response = await drive.files.create({
            requestBody: folderMetadata,
            fields: 'id',
        });
        const file: drive_v3.Schema$File = response.data;
        console.log('Folder Id:', file.id);
        return file.id!;
    } catch (err) {
        // TODO: Handle error
        throw err;
    }

}

export async function createFolderInFolder(authClient: OAuth2Client, folderName: string, destinationFolderId: string = "1WrZoY424ZxdKHwcEcz3y__-k8EZjd0yB") {
    //De moment, només tinc carpeta ES
    const drive = google.drive({ version: 'v3', auth: authClient });

    const folderMetadata: drive_v3.Schema$File = {
        name: folderName,
        mimeType: 'application/vnd.google-apps.folder',
        parents: [destinationFolderId],
    };
    try {
        const response = await drive.files.create({
            requestBody: folderMetadata,
            fields: 'id',
        });
        const file: drive_v3.Schema$File = response.data;
        console.log('Folder Id:', file.id);
        return file.id!;
    } catch (err) {
        throw err;
    }

}

export async function createFileInFolder(authClient: OAuth2Client, folderId: string) {

    const drive = google.drive({ version: 'v3', auth: authClient });

    const fileMetadata: drive_v3.Schema$File = {
        name: "fitxer",
        mimeType: 'application/vnd.google-apps.document',
        parents: [folderId],
    };
    try {
        const response = await drive.files.create({
            requestBody: fileMetadata,
            fields: 'id',
        });
        const file: drive_v3.Schema$File = response.data;
        console.log('File Id:', file.id);
        return file.id!;
    } catch (err) {
        throw err;
    }



}

export async function createFile(authClient: OAuth2Client) {
    const drive = google.drive({ version: 'v3', auth: authClient });
    const fileMetadata: drive_v3.Schema$File = {
        name: "fitxer",
        mimeType: 'application/vnd.google-apps.document',
    };
    const res = await drive.files.create({
        requestBody: fileMetadata,
        fields: 'id',
    }
    );
    return res.data;
}

export async function updateFile(authClient: any, fileId: string, log:string) {

    const docs = google.docs({
        version: 'v1',
        auth: authClient,
      });
            
      const requests = [
        {
          insertText: {
            text: log,
            endOfSegmentLocation: {},
          },
        },
      ];
      
      docs.documents.batchUpdate({
        documentId: fileId,
        requestBody: {
          requests,
        },
      }).then(response => {
        console.log('Content appended successfully!');
        return true;
      }).catch(error => {
        console.error('Error appending content:', error);
        return false;
        
      });

   
}


