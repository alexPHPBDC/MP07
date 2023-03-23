import { authenticate } from "@google-cloud/local-auth";
import { drive_v3, google } from "googleapis";
import { OAuth2Client } from "googleapis-common";
import { jsonDatabase } from "../Interfaces/interfaces";
import { Database } from "./Database";
const fs = require('fs').promises;
const path = require('path');
const drive = require('debug')("drive");
const error = require('debug')("error");

export class Drive {
    private static instance: Drive;
    private readonly TOKEN_PATH = path.join(process.cwd(), 'token.json');
    private readonly CREDENTIALS_PATH = path.join(process.cwd(), 'credentials.json');
    private readonly SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
        'https://www.googleapis.com/auth/drive.metadata',
        'https://www.googleapis.com/auth/drive.metadata.readonly',
        'https://www.googleapis.com/auth/drive.photos.readonly',
        'https://www.googleapis.com/auth/drive.readonly',
        'https://www.googleapis.com/auth/drive.scripts'
    ];
    private constructor() {
        
    }

    public async getOAuth() {
        let oauth = await this.authorize().then((auth) => {
            return auth;
        }).catch((err) => {
            return undefined;
        });

        return oauth;
    }
    public static get Instance() {
        return this.instance || (this.instance = new this());
    }

    async authorize() {
        let client = await this.loadSavedCredentialsIfExist();
        
        if (client) {
            return client;
        }

        client = await authenticate({
            scopes: this.SCOPES,
            keyfilePath: this.CREDENTIALS_PATH,
        });
        if (client.credentials) {
            await this.saveCredentials(client);
        }
        return client;
    }

    async saveCredentials(client: any) {
        const content = await fs.readFileS(this.CREDENTIALS_PATH);
        const keys = JSON.parse(content);
        const key = keys.installed || keys.web;
        const payload = JSON.stringify({
            type: 'authorized_user',
            client_id: key.client_id,
            client_secret: key.client_secret,
            refresh_token: client.credentials.refresh_token,
        });
        await fs.writeFile(this.TOKEN_PATH, payload);
    }

    async loadSavedCredentialsIfExist(): Promise<OAuth2Client | null> {
        try {
            const content = await fs.readFile(this.TOKEN_PATH);
            const credentials = JSON.parse(content);
            return google.auth.fromJSON(credentials) as OAuth2Client;
        } catch (err) {
            return null;
        }
    }

    public async createFolderInFolder(authClient: OAuth2Client, folderName: string, destinationFolderId: string = "1WrZoY424ZxdKHwcEcz3y__-k8EZjd0yB") {
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

    public async createFileInFolder(authClient: OAuth2Client, folderId: string) {

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
            return file.id!;
        } catch (err) {
            throw err;
        }



    }

    public async createFile(authClient: OAuth2Client) {
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

    public async updateFile(authClient: any, fileId: string, log: string) {

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
            drive('Content appended successfully!');
            return true;
        }).catch(error => {
            error('Error appending content:', error);
            return false;

        });


    }

    public async crearFolderDavui(OAuth2Client: OAuth2Client, today: string, serverFolderId: string, serverID: string) {
        let serverFolderTodayId = await
            this.createFolderInFolder(OAuth2Client, today, serverFolderId)//Creo el folder del dia d'avui
                .then(
                    function (folderId) {

                        return folderId;
                    }
                )
        return serverFolderTodayId
    }

    //Creo el folder del canal d'avui
    public async crearFolderCanalAvui(OAuth2Client: OAuth2Client, channelName: string, serverFolderTodayId: string) {
        let serverFolderTodayChannelId = await
            this.createFolderInFolder(OAuth2Client, channelName, serverFolderTodayId).then(function (folderId) {
                return folderId;
            });
        return serverFolderTodayChannelId;
    }

    public async crearFolderThreadCanalAvui(OAuth2Client: OAuth2Client, threadName: string, serverFolderTodayChannelId: string) {

        let serverFolderTodayChannelThreadId = await
            this.createFolderInFolder(OAuth2Client, threadName, serverFolderTodayChannelId).then(function (folderId) {
                return folderId;
            });

        return serverFolderTodayChannelThreadId;
    }

    public async crearFitxerAvuiCanalAvui(OAuth2Client: OAuth2Client, serverFolderTodayChannelId: string) {
        let fitxerTodayId = await
            this.createFileInFolder(OAuth2Client, serverFolderTodayChannelId).then(function (fileId) {


                return fileId;
            });
        return fitxerTodayId;
    }

    public async crearFitxerThreadCanalAvui(OAuth2Client: OAuth2Client, serverFolderTodayChannelThreadId: string) {
        let fitxerTodayId = await
            this.createFileInFolder(OAuth2Client, serverFolderTodayChannelThreadId).then(function (fileId) {
                return fileId;
            });
        return fitxerTodayId;
    }

    public async crearFolderServidor(OAuth2Client: OAuth2Client, folderName: string) {

        let serverFolderId = await this.createFolderInFolder(OAuth2Client, folderName).then(function (folderId) {
            return folderId;
        })

        return serverFolderId;

    }

    public async obtenirCarpetaServidor(OAuth: OAuth2Client, idServer: string, dadesLocals:jsonDatabase) {
        let serverName = dadesLocals["serverids"][idServer]["serverName"];
        let folderName = serverName + "_" + idServer;
        let serverFolderId = dadesLocals["serverids"][idServer]["driveFolderId"];

        if (serverFolderId === undefined) {
            serverFolderId = await this.crearFolderServidor(OAuth, folderName);
        }
        return serverFolderId;
    }
    public async obtenirCarpetaAvui(OAuth: OAuth2Client, dia: string, serverFolderId: string, idServer: string, dadesLocals:jsonDatabase) {
        let serverFolderTodayId = dadesLocals["serverids"][idServer]["days"][dia]["driveFolderId"];
        if (serverFolderTodayId === undefined) {
            serverFolderTodayId = await this.crearFolderDavui(OAuth, dia, serverFolderId, idServer);
        }
        return serverFolderTodayId;
    }
}