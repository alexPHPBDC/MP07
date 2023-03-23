

import { AnyThreadChannel, Channel, GuildTextBasedChannel, Message } from 'discord.js';
import fs from 'fs';
import { OAuth2Client } from 'google-auth-library';
import { jsonDatabase } from '../Interfaces/interfaces';
import { isJsonDatabase } from '../utils';
import { Drive } from './Drive';

export class Database {
    hanCanviatDades: boolean = false;
    private static instance: Database;
    private constructor() {
    }
    public static get Instance() {
        return this.instance || (this.instance = new this());
    }

    saveJSON(filename: string, data: any) {
        var newData = JSON.stringify(data);
        fs.writeFileSync(filename, newData);
    }

    loadJSON(filename: string) {
        return JSON.parse(fs.readFileSync(filename, 'utf-8'));
    }

    verifyJSON(filename: string, json: any, serverID: string, today: string, channel: GuildTextBasedChannel, serverFolderName: any, thread: AnyThreadChannel | undefined = undefined) {
        if (json["serverids"] === undefined) {
            json["serverids"] = {};
        }

        if (json["serverids"][serverID] === undefined) {
            json["serverids"][serverID] = {};
            json["serverids"][serverID]["serverName"] = serverFolderName;
        }

        if (json["serverids"][serverID]["days"] === undefined) {
            json["serverids"][serverID]["days"] = {};
        }
        if (json["serverids"][serverID]["days"][today] === undefined) {
            json["serverids"][serverID]["days"][today] = {};
        }
        if (json["serverids"][serverID]["days"][today]["channels"] === undefined) {
            json["serverids"][serverID]["days"][today]["channels"] = {};
        }
        if (json["serverids"][serverID]["days"][today]["channels"][channel.id] === undefined) {
            json["serverids"][serverID]["days"][today]["channels"][channel.id] = {};
            json["serverids"][serverID]["days"][today]["channels"][channel.id]["channelName"] = channel.name;
        }
        if (json["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"] === undefined) {
            json["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"] = {};
        }


        if (thread !== undefined) {
            if (json["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id] === undefined) {
                json["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id] = {};
                json["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["threadName"] = thread.name;
            }
        }

        this.saveJSON(filename, json);
        return json;
    }

    guardarLocal(serverID: string, today: string, channel: GuildTextBasedChannel, serverFolderName: string, log: string, thread: AnyThreadChannel | undefined = undefined) {

        let dadesLocals = this.loadJSON("localData.json");
        dadesLocals = this.verifyJSON("localData.json", dadesLocals, serverID, today, channel, serverFolderName, thread);

        if (thread !== undefined) {

            if (dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["content"] === undefined) {
                dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["content"] = "";
            }

            dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["content"] += log;
        } else {

            if (dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["content"] === undefined) {
                dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["content"] = "";
            }
            dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["content"] += log;

        }

        this.saveJSON("localData.json", dadesLocals);
        this.hanCanviatDades = true;
    }

    calSincronitzar(dadesLocals: any | jsonDatabase): boolean {

        if (!isJsonDatabase(dadesLocals)) {
            return false;
        }
        if (!this.hanCanviatDades) {
            return false;
        }
        return true;
    }

    async sincronitzarDadesLocalsAmbDrive(OAuth: OAuth2Client) {

        let dadesLocals: any | jsonDatabase = this.loadJSON("localData.json");

        if (this.calSincronitzar(dadesLocals)) {

            //Per tal de que no es sincronitzin les dades cada cop que es crida la funció

            let servidors = dadesLocals["serverids"];

            try {
                await this.sincronitzarServidors(OAuth, servidors, dadesLocals);
                this.saveJSON("localDataAnterior.json", this.loadJSON("localData.json"));
                this.hanCanviatDades = false;
            } catch (err) {
                throw new Error("Error al sincronitzar els servidors: " + err);
            }

        }

    }

    async sincronitzarServidors(OAuth: any, servidors: any, dadesLocals: any) {
        for (let idServer of Object.keys(servidors)) {
            try {
                let serverFolderId = await Drive.Instance.obtenirCarpetaServidor(OAuth, idServer, dadesLocals);
                dadesLocals["serverids"][idServer]["driveFolderId"] = serverFolderId;
                this.saveJSON("localData.json", dadesLocals);

                let dies = dadesLocals["serverids"][idServer]["days"];

                await this.sincronitzarDies(OAuth, idServer, dies, serverFolderId, dadesLocals)

            } catch (err) {
                if (idServer) {
                    throw new Error("Error al sincronitzar el servidor: " + idServer + " " + err);
                }
                throw new Error("Error al sincronitzar el servidor: " + err);
            }


        }
    }
    async sincronitzarDies(OAuth: any, idServer: any, dies: any, serverFolderId: any, dadesLocals: any) {
        for (let dia of Object.keys(dies)) {
            try {
                let serverFolderTodayId = await Drive.Instance.obtenirCarpetaAvui(OAuth, dia, serverFolderId, idServer, dadesLocals);
                dadesLocals["serverids"][idServer]["days"][dia]["driveFolderId"] = serverFolderTodayId;
                this.saveJSON("localData.json", dadesLocals);

                let canals = dadesLocals["serverids"][idServer]["days"][dia]["channels"];

                await this.sincronitzarCanals(OAuth, idServer, dia, canals, serverFolderTodayId, dadesLocals)
            } catch (err) {
                if (dia) {
                    throw new Error("Error al sincronitzar el dia: " + dia + " " + err);
                }
                throw new Error("Error al sincronitzar el dia: " + err);
            }


        }
    }
    async sincronitzarCanals(OAuth: any, idServer: any, dia: any, canals: any, serverFolderTodayId: any, dadesLocals: any) {
        for (let channelId of Object.keys(canals)) {
            try {

                let canal = dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId];
                let channelName = canal["channelName"];
                let serverFolderTodayChannelId = canal["driveFolderId"];

                if (serverFolderTodayChannelId === undefined) {
                    serverFolderTodayChannelId = await Drive.Instance.crearFolderCanalAvui(OAuth, channelName, serverFolderTodayId);
                }
                dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["driveFolderId"] = serverFolderTodayChannelId;
                this.saveJSON("localData.json", dadesLocals);

                //Creo el fitxer d'avui
                let fitxerTodayId = dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["driveFileId"];
                let content = dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["content"];
                if (fitxerTodayId === undefined) {
                    fitxerTodayId = await Drive.Instance.crearFitxerAvuiCanalAvui(OAuth, serverFolderTodayChannelId);
                }
                dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["driveFileId"] = fitxerTodayId;
                this.saveJSON("localData.json", dadesLocals);

                if (content !== "") {//Si hi ha contingut nou, l'afegeixo al fitxer
                    await Drive.Instance.updateFile(OAuth, fitxerTodayId, content);
                    dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["content"] = "";
                    this.saveJSON("localData.json", dadesLocals);
                }

                //Per cada thread, si no tinc la carpeta del thread, la creo
                let threads = canal["threads"];

                await this.syncronitzarThreads(OAuth, dadesLocals, idServer, dia, channelId, serverFolderTodayChannelId, threads);

            } catch (err) {
                if (channelId) {
                    throw new Error("Error al sincronitzar el canal: " + channelId + " " + err);
                }
                throw new Error("Error al sincronitzar el canal: " + err);
            }

        }
    }



    async syncronitzarThreads(OAuth: any, dadesLocals: any, idServer: any, dia: any, channelId: any, serverFolderTodayChannelId: any, threads: any) {
        for (let threadId of Object.keys(threads)) {
            try {
                let thread = threads[threadId];
                let serverFolderTodayChannelThreadId = thread["driveFolderId"];
                let threadName = thread["threadName"];

                if (serverFolderTodayChannelThreadId === undefined) {
                    serverFolderTodayChannelThreadId = await Drive.Instance.crearFolderThreadCanalAvui(OAuth, threadName, serverFolderTodayChannelId);
                }

                dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["threads"][threadId]["driveFolderId"] = serverFolderTodayChannelThreadId;
                this.saveJSON("localData.json", dadesLocals);

                //Creo el fitxer d'avui
                let fitxerTodayThreadId = thread["driveFileId"];
                let contentThread = thread["content"];
                if (fitxerTodayThreadId === undefined) {
                    fitxerTodayThreadId = await Drive.Instance.crearFitxerThreadCanalAvui(OAuth, serverFolderTodayChannelThreadId);
                }
                dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["threads"][threadId]["driveFileId"] = fitxerTodayThreadId;
                this.saveJSON("localData.json", dadesLocals);

                if (contentThread !== "") {//Si hi ha contingut nou, l'afegeixo al fitxer
                    await Drive.Instance.updateFile(OAuth, fitxerTodayThreadId, contentThread);
                    dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["threads"][threadId]["content"] = "";
                    this.saveJSON("localData.json", dadesLocals);
                }
            } catch (err) {
                if (threadId) {
                    throw new Error("Error al sincronitzar el thread: " + threadId + " " + err);
                }
                throw new Error("Error al sincronitzar el thread: " + err);
            }
        }
    }

}