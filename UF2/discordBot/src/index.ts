import dotenv from "dotenv";
dotenv.config();
import { Client, GatewayIntentBits, Channel, GuildTextBasedChannel, Message, PartialMessage, NonThreadGuildBasedChannel, AnyThreadChannel } from "discord.js";
import process from 'process';
import { createFolderInFolder, authorize, createBotFolder, createFile, listFiles, createFileInFolder, updateFile } from "./drive";
import { loadJSON, saveJSON, verifyJSON } from "./database";
import { Subscription } from "rxjs";
import { Configuration, OpenAIApi } from "openai";
import axios from "axios";
import FormData from "form-data";
import fs from "fs";
import { createWriteStream } from 'fs';
import ffmpeg from "fluent-ffmpeg";
import path from "path";

import { joinVoiceChannel } from '@discordjs/voice';
import { ids } from "googleapis/build/src/apis/ids";
import { OAuth2Client } from "google-auth-library";
import { instance } from "gaxios";
import { oauth2_v2 } from "googleapis";

const configuration = new Configuration({
    organization: "org-ZCsMMGSG5fNg8kr0QvPKkQWP",
    apiKey: process.env.OPENAI_API_KEY,
});
const openai = new OpenAIApi(configuration);

let json = require('../database.json');
const bot = require('debug')("bot");

const client = new Client({
    intents: [
        GatewayIntentBits.Guilds,
        GatewayIntentBits.GuildMessages,
        GatewayIntentBits.MessageContent,
        GatewayIntentBits.GuildVoiceStates,
        GatewayIntentBits.GuildMessageReactions,

    ],
});

client.once("ready", () => {
    console.log(`${client?.user?.username} is online`);

});

let channels = new Map();
client.on('voiceStateUpdate', async (oldState, newState) => {
    // Check if the user joined a new voice channel

    if (!newState.channel || oldState.channel === newState.channel) {
        return;
    }

    //   // Check if the bot is already connected to the channel
    if (channels.has(newState.channel.id)) {
        return;
    }

    // Connect to the new voice channel
    const connection = await joinVoiceChannel({
        channelId: newState.channel.id,
        guildId: newState.guild.id,
        adapterCreator: newState.guild.voiceAdapterCreator,
    });
    const receiver = connection.receiver;
    const stream = receiver.subscribe(newState.member?.id!);

    // Store the connection and stream in the map
    channels.set(newState.channel.id, { connection, stream });

    // Do something with the audio stream
    const writableStream = fs.createWriteStream('audio.wav');
    // Create a writable stream to save the audio data
    stream.pipe(writableStream);

    // Do something with the audio stream
    //   stream.on('data', (chunk) => {
    //     outputStream.write(chunk);
    //   });
    setTimeout(() => {
        // Convert the WAV file to an MP3 file
        writableStream.end();
        wavToMp3('audio.wav');
    }, 10000);



    // handleStream(stream);

    // Listen for when the user leaves the voice channel
    //   newState.channel.on('leave', () => {
    //     channels.delete(newState.channel.id);
    //   });


    // newState.channel.on('leave', () => {

    // Close the writable stream








});

//S'uneix a un servidor
client.on("guildCreate", async guild => {
    console.log("Joined a new guild: " + guild.name);
    //Your other stuff like adding to guildArray
    var serverID = guild?.id ?? "ERRORID";
    var serverName = guild?.name ?? "ERRORNAME";
    var folderName = serverName + "_" + serverID;
    await authorize().then((OAuth) => async function (OAuth: OAuth2Client, folderName: string) {
        await crearFolderServidor(OAuth, folderName);
    }).catch(console.error);

})

function guardarLocal(serverID: string, today: string, channel: GuildTextBasedChannel, serverFolderName: string, log: string, thread: AnyThreadChannel | undefined = undefined) {

    let dadesLocals = loadJSON("localData.json");
    dadesLocals = verifyJSON("localData.json", dadesLocals, serverID, today, channel, serverFolderName, thread);

    if (thread !== undefined) {
        dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["content"] ?? "";
        dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["content"] += log;
    } else {
        dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["content"] ?? "";
        dadesLocals["serverids"][serverID]["days"][today]["channels"][channel.id]["content"] += log;

    }

    saveJSON("localData.json", dadesLocals);
}

setInterval(() => {
    sincronitzarDades();
}, 20 * 1000);

//Cada 5 minuts, sincronitzo les dades amb google drive
//Això vol dir que cada 5 minuts, es creen els fitxers de logs dels canals
//A més de les carpetes del drive

async function obtenirCarpetaServidor(OAuth: OAuth2Client, idServer: string) {
    let dadesLocals = loadJSON("localData.json");
    let serverName = dadesLocals["serverids"][idServer]["serverName"];
    let folderName = serverName + "_" + idServer;
    let serverFolderId = dadesLocals["serverids"][idServer]["driveFolderId"];
    if (serverFolderId === undefined) {
        serverFolderId = await crearFolderServidor(OAuth, folderName);
    }
    return serverFolderId;
}
async function obtenirCarpetaAvui(OAuth: OAuth2Client, dia: string, serverFolderId: string, idServer: string) {
    let dadesLocals = loadJSON("localData.json");
    let serverFolderTodayId = dadesLocals["serverids"][idServer]["days"][dia]["driveFolderId"];
    if (serverFolderTodayId === undefined) {
        serverFolderTodayId = await crearFolderDavui(OAuth, dia, serverFolderId, idServer);
    }
    return serverFolderTodayId;
}

function isJsonDatabase(dadesLocals: any | jsonDatabase): dadesLocals is jsonDatabase {
    let dades = dadesLocals as jsonDatabase;
    let isOK = "serverids" in dadesLocals;
    if (!isOK) return false;
    let idServer = Object.keys(dades.serverids)[0];
    if (idServer === undefined) return false;
    isOK = isOK && "serverName" in dades.serverids[idServer];
    isOK = isOK && "days" in dades.serverids[idServer];
    let dia = Object.keys(dades.serverids[idServer].days)[0];
    if (dia === undefined) return false;
    isOK = isOK && "channels" in dades.serverids[idServer].days[dia];
    let idChannel = Object.keys(dades.serverids[idServer].days[dia].channels)[0];
    if (idChannel === undefined) return false;
    isOK = isOK && "channelName" in dades.serverids[idServer].days[dia].channels[idChannel];
    isOK = isOK && "content" in dades.serverids[idServer].days[dia].channels[idChannel];
    isOK = isOK && "threads" in dades.serverids[idServer].days[dia].channels[idChannel];


    return isOK;

}

async function sincronitzarDades() {

    //Si les dades de fa 5 minuts són diferents a les actuals
    //Aleshores, sincronitzo les dades
    let dadesLocalsAnteriors: any = loadJSON("localDataAnterior.json");
    let dadesLocals: any | jsonDatabase = loadJSON("localData.json");

    //TODO per tal de que funcioni, cal que les dades locals siguin un jsonDatabase, altrament donaria errors
    //if(dadesLocals instanceof(jsonDatabase)){}


    if (isJsonDatabase(dadesLocals)) {

        if (JSON.stringify(dadesLocals) !== JSON.stringify(dadesLocalsAnteriors)) {


            authorize().then(async (OAuth: OAuth2Client) => {

                //Per cada servidor, si no tinc la carpeta del servidor al drive, la creo
                let servidors = dadesLocals["serverids"];

                for (let idServer of Object.keys(servidors)) {
                    console.log(idServer);
                    let dies = dadesLocals["serverids"][idServer]["days"];
                    let serverFolderId = await obtenirCarpetaServidor(OAuth, idServer);
                    dadesLocals["serverids"][idServer]["driveFolderId"] = serverFolderId;
                    saveJSON("localData.json", dadesLocals);

                    //Per cada dia, si no tinc la carpeta del dia, la creo
                    for (let dia of Object.keys(dies)) {
                        let canals = dadesLocals["serverids"][idServer]["days"][dia]["channels"];
                        let serverFolderTodayId = await obtenirCarpetaAvui(OAuth, dia, serverFolderId, idServer);
                        dadesLocals["serverids"][idServer]["days"][dia]["driveFolderId"] = serverFolderTodayId;
                        saveJSON("localData.json", dadesLocals);

                        for (let channelId of Object.keys(canals)) {
                            let canal = dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId];

                            let serverFolderTodayChannelId = canal["driveFolderId"];
                            let channelName = canal["channelName"];

                            if (serverFolderTodayChannelId === undefined) {
                                serverFolderTodayChannelId = await crearFolderCanalAvui(OAuth, channelId, dia, channelName, serverFolderTodayId, idServer);
                            }
                            dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["driveFolderId"] = serverFolderTodayChannelId;
                            saveJSON("localData.json", dadesLocals);

                            //Creo el fitxer d'avui
                            let fitxerTodayId = dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["driveFileId"];
                            let content = dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["content"];
                            if (fitxerTodayId === undefined) {
                                fitxerTodayId = await crearFitxerAvuiCanalAvui(OAuth, channelId, dia, serverFolderTodayChannelId, idServer);
                            }
                            dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["driveFileId"] = fitxerTodayId;
                            saveJSON("localData.json", dadesLocals);

                            if (content !== "") {//Si hi ha contingut nou, l'afegeixo al fitxer
                                await updateFile(OAuth, fitxerTodayId, content);
                                dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["content"] = "";
                                saveJSON("localData.json", dadesLocals);
                            }


                            //Per cada thread, si no tinc la carpeta del thread, la creo
                            let threads = canal["threads"];
                            for (let threadId of Object.keys(threads)) {
                                let thread = threads[threadId];
                                let serverFolderTodayChannelThreadId = thread["driveFolderId"];
                                let threadName = thread["threadName"];

                                if (serverFolderTodayChannelThreadId === undefined) {
                                    serverFolderTodayChannelThreadId = await crearFolderThreadCanalAvui(OAuth, threadName, serverFolderTodayChannelId);
                                }

                                dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["threads"][threadId]["driveFolderId"] = serverFolderTodayChannelThreadId;
                                saveJSON("localData.json", dadesLocals);

                                //Creo el fitxer d'avui
                                let fitxerTodayThreadId = thread["driveFileId"];
                                let contentThread = thread["content"];
                                if (fitxerTodayThreadId === undefined) {
                                    fitxerTodayThreadId = await crearFitxerThreadCanalAvui(OAuth, serverFolderTodayChannelThreadId);
                                }
                                dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["threads"][threadId]["driveFileId"] = fitxerTodayThreadId;
                                saveJSON("localData.json", dadesLocals);

                                if (contentThread !== "") {//Si hi ha contingut nou, l'afegeixo al fitxer
                                    await updateFile(OAuth, fitxerTodayThreadId, contentThread);
                                    dadesLocals["serverids"][idServer]["days"][dia]["channels"][channelId]["threads"][threadId]["content"] = "";
                                    saveJSON("localData.json", dadesLocals);
                                }
                            }


                        }


                    }
                }

                let dadesLocalsAnteriors = dadesLocals;
                saveJSON("localDataAnterior.json", dadesLocalsAnteriors);

            }).catch(console.error);
            //Per cada servidor, si no tinc la carpeta del servidor, la creo
        }
    }
}

client.on("messageDelete", (message) => {
    let { today, serverID, channel, serverFolderName, thread } = preparedData(message);

    let log = "\n [" + new Date().toUTCString() + "] Message deleted: " + message.content + " by " + message.author?.username + " at " + message.createdAt.toUTCString();

    guardarLocal(serverID, today, channel, serverFolderName, log, thread);
    //guardarADrive(serverID, today, channel, serverFolderName, log, thread);

});

client.on("messageUpdate", (oldMessage, newMessage) => {
    console.log("updated");


    let { today, serverID, channel, serverFolderName, thread } = preparedData(newMessage);
    let log = "\n [" + newMessage.createdAt.toUTCString() + "] Message updated: " + oldMessage.content + " by " + oldMessage.author?.username + " at " + oldMessage.createdAt.toUTCString() + " to " + newMessage.content + " at " + newMessage.createdAt.toUTCString();
    //guardarADrive(serverID, today, channel, serverFolderName, log, thread);
    guardarLocal(serverID, today, channel, serverFolderName, log, thread);

});

client.on("messageCreate", async (message: Message) => {
    console.log("created");
    let { today, serverID, channel, serverFolderName, thread } = preparedData(message);

    let log = "\n [" + message.createdAt.toUTCString() + "] Message created: " + message.content + " by " + message.author?.username;
    //guardarADrive(serverID, today, channel, serverFolderName, log, thread);
    guardarLocal(serverID, today, channel, serverFolderName, log, thread);

});

client.on("messageReactionAdd", (reaction, user) => {
    let { today, serverID, channel, serverFolderName, thread } = preparedData(reaction.message);

    let log = "\n [" + new Date().toISOString() + "] Reaction " + reaction.emoji.name + " added by " + user.username + " on message:" + reaction.message.content + " from the user " + reaction.message?.author?.username;

    //guardarADrive(serverID, today, channel, serverFolderName, log, thread);
    guardarLocal(serverID, today, channel, serverFolderName, log, thread);

});

client.on("messageReactionRemove", (reaction, user) => {
    let { today, serverID, channel, serverFolderName, thread } = preparedData(reaction.message);

    let log = "\n Reaction removed: " + reaction.emoji.name + " by " + user.username + " at " + reaction.message.createdAt.toUTCString() + "from" + reaction.message.content;
    //guardarADrive(serverID, today, channel, serverFolderName, log, thread);
    guardarLocal(serverID, today, channel, serverFolderName, log, thread);

});

client.on("messageReactionRemoveEmoji", (reaction) => {
    let { today, serverID, channel, serverFolderName, thread } = preparedData(reaction.message);

});

client.on("threadCreate", (thread: AnyThreadChannel) => {
    let name = thread.name;
    let channelId = thread.parentId;
    let messageId = thread.id;
    let guildId = thread.guildId;

    let log = "thread created at ajksldjk"

    // name: 'Thread 2',
    // parentId: '1079778507023990815',

    // let { today, serverID, channel, serverFolderName } = preparedData(reaction.message);

});

client.on("threadDelete", (thread: AnyThreadChannel) => {
});


// messageReactionRemoveEmoji: [reaction: MessageReaction | PartialMessageReaction];
// messageReactionAdd: [reaction: MessageReaction | PartialMessageReaction, user: User | PartialUser];
// messageReactionRemove: [reaction: MessageReaction | PartialMessageReaction, user: User | PartialUser];
// threadCreate: [thread: AnyThreadChannel, newlyCreated: boolean];
// threadDelete: [thread: AnyThreadChannel];
// threadListSync: [threads: Collection<Snowflake, AnyThreadChannel>, guild: Guild];
// threadMemberUpdate: [oldMember: ThreadMember, newMember: ThreadMember];
// threadMembersUpdate: [
//   addedMembers: Collection<Snowflake, ThreadMember>,
//   removedMembers: Collection<Snowflake, ThreadMember | PartialThreadMember>,
//   thread: AnyThreadChannel,
// ];
// threadUpdate: [oldThread: AnyThreadChannel, newThread: AnyThreadChannel];
// typingStart: [typing: Typing];
// voiceStateUpdate: [oldState: VoiceState, newState: VoiceState];

function preparedData(message: Message | PartialMessage) {
    let today = new Date().toISOString().split('T')[0];
    let serverID = message.guild?.id ?? "ERRORID";
    let serverFolderName = message.guild?.name ?? "ERRORNAME" + "_" + serverID;
    let channel: any = message.channel;
    let thread = undefined;
    let aux = undefined;

    if (channel.isThread()) {
        aux = channel;
        channel = channel.parent as GuildTextBasedChannel;
        thread = aux;
    }

    return {
        today: today,
        serverID: serverID,
        channel: channel,
        serverFolderName: serverFolderName,
        thread: thread
    }
}

// async function guardarADrive(serverID: string, today: string, channel: GuildTextBasedChannel, serverFolderName: string, log: string, thread: AnyThreadChannel | undefined = undefined) {
//     let OAuth2Client = await authorize().catch(console.error);
//     let serverFolderId = json["serverids"][serverID]["driveFolderId"];
//     if (serverFolderId === undefined) {
//         serverFolderId = await crearFolderServidor(OAuth2Client, serverFolderName, serverID);
//     }

//     json = verifyJSON(json, serverID, today, channel, thread);

//     let serverFolderTodayId = json["serverids"][serverID]["days"][today]["driveFolderId"];
//     if (serverFolderTodayId === undefined) {
//         serverFolderTodayId = await crearFolderDavui(OAuth2Client, today, serverFolderId, serverID);
//     }

//     let serverFolderTodayChannelId = json["serverids"][serverID]["days"][today]["channels"][channel.id]["driveFolderId"];
//     if (serverFolderTodayChannelId === undefined) {
//         serverFolderTodayChannelId = await crearFolderCanalAvui(OAuth2Client, channel.id, today, channel.name, serverFolderTodayId, serverID);
//     }


//     if (thread !== undefined) {

//         let serverFolderTodayChannelThreadId = json["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["driveFolderId"];
//         if (serverFolderTodayChannelThreadId === undefined) {
//             serverFolderTodayChannelThreadId = await crearFolderThreadAvuiCanal(OAuth2Client, thread.id, channel.id, today, thread.name, serverFolderTodayChannelId, serverID);
//         }

//         let fitxerTodayId = json["serverids"][serverID]["days"][today]["channels"][channel.id]["threads"][thread.id]["driveFileId"];
//         if (fitxerTodayId === undefined) {
//             fitxerTodayId = await crearFitxerAvuiCanalAvuiThread(OAuth2Client, channel.id, thread.id, today, serverFolderTodayChannelThreadId, serverID);
//         }
//         await updateFile(OAuth2Client, fitxerTodayId, log);
//     } else {
//         let fitxerTodayId = json["serverids"][serverID]["days"][today]["channels"][channel.id]["driveFileId"];

//         if (fitxerTodayId === undefined) {
//             fitxerTodayId = await crearFitxerAvuiCanalAvui(OAuth2Client, channel.id, today, serverFolderTodayChannelId, serverID);
//         }

//         await updateFile(OAuth2Client, fitxerTodayId, log);
//     }



// }


async function crearFolderServidor(OAuth2Client: OAuth2Client, folderName: string) {

    let serverFolderId = await createFolderInFolder(OAuth2Client, folderName).then(function (folderId) {


        return folderId;

    }).catch(console.error)

    return serverFolderId;

}

async function crearFolderDavui(OAuth2Client: any, today: string, serverFolderId: string, serverID: string) {
    let serverFolderTodayId = await
        createFolderInFolder(OAuth2Client, today, serverFolderId)//Creo el folder del dia d'avui
            .then(
                function (folderId) {

                    return folderId;
                }
            )
    return serverFolderTodayId
}

//Creo el folder del canal d'avui
async function crearFolderCanalAvui(OAuth2Client: any, channelId: string, today: string, channelName: string, serverFolderTodayId: string, serverID: string) {
    let serverFolderTodayChannelId = await
        createFolderInFolder(OAuth2Client, channelName, serverFolderTodayId).then(function (folderId) {
            return folderId;
        });
    return serverFolderTodayChannelId;
}

async function crearFolderThreadCanalAvui(OAuth2Client: any, threadName: string, serverFolderTodayChannelId: string) {

    let serverFolderTodayChannelThreadId = await
        createFolderInFolder(OAuth2Client, threadName, serverFolderTodayChannelId).then(function (folderId) {
            return folderId;
        });

    return serverFolderTodayChannelThreadId;
}

async function crearFitxerAvuiCanalAvui(OAuth2Client: any, channelId: string, today: string, serverFolderTodayChannelId: string, serverID: string) {
    let fitxerTodayId = await
        createFileInFolder(OAuth2Client, serverFolderTodayChannelId).then(function (fileId) {


            return fileId;
        }).catch(console.error);
    return fitxerTodayId;
}

async function crearFitxerThreadCanalAvui(OAuth2Client: any, serverFolderTodayChannelThreadId: string) {
    let fitxerTodayId = await
        createFileInFolder(OAuth2Client, serverFolderTodayChannelThreadId).then(function (fileId) {
            return fileId;
        }).catch(console.error);
    return fitxerTodayId;
}

function convertirAText(message: Message<boolean>) {

    const token = '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';
    const openAiURL = 'https://api.openai.com/v1/audio/transcriptions';

    const attachment = message.attachments.first();
    if (!attachment) {
        // Handle case where message has no attachments
        return message;
    }
    const attachmentUrl = attachment.url;

    axios({
        method: 'get',
        url: attachmentUrl,
        responseType: 'stream'
    }).then(function (response) {
        // Create a write stream to the local file
        const fileStream = fs.createWriteStream("audio.m4a");

        // Pipe the response stream to the file stream
        response.data.pipe(fileStream);

        // Listen for the 'finish' event to know when the download is complete
        fileStream.on('finish', function () {
            setTimeout(() => {
                const formData = new FormData();
                formData.append('file', fs.createReadStream('audio.m4a'));
                formData.append('model', 'whisper-1');
                axios.post(openAiURL, formData, {
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': `multipart/form-data`,
                        ...formData.getHeaders()
                    }
                })
                    .then(response => {
                        console.log(response.data);
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }, 5000); // Wait for 5 seconds before making the API request

        });
    }).catch(function (error) {





    });
    return message;
}


function wavToMp3(wavFilename: string): Promise<string> {
    return new Promise((resolve, reject) => {
        //   if (!isWavFile(wavFilename)) {
        //     throw new Error(`Not a wav file`);
        //   }
        const outputFile = wavFilename.replace(".wav", ".mp3");
        ffmpeg({
            source: wavFilename,
        }).on("error", (err) => {
            reject(err);
        }).on("end", () => {
            resolve(outputFile);
        }).save(outputFile);
    });
}

function handleStream(stream: any) {
    // Do something with the audio stream
    console.log('Received audio stream');
    stream.on('data', (chunk: any) => {
        console.log('Received audio chunk');
    });

}

authorize().then(listFiles).catch(console.error);

client.login(process.env.TOKEN);

export interface keyValue {
    [key: string]: string;
}

export interface jsonDatabase {
    serverids: {
        [serverId: string]: {
            serverName: string;
            days: {
                [date: string]: {
                    channels: {
                        [channelId: string]: {
                            threads: {
                                [threadId: string]: {
                                    threadName: string;
                                    content: string;
                                }
                            };
                            channelName: string;
                            content: string;
                        };
                    };
                };
            };
        };
    };
}

export interface jsonServer {
    [serverID: string]: {
        driveFolderId: string | null;
        days: {
            [date: string]: {
                channels: {
                    [channelId: string]: {
                        threads: {
                            [threadId: string]: {
                                driveFolderId: string;
                                driveFileId: string;
                            }
                        },
                        driveFolderId: string,
                        driveFileId: string,
                    };
                };
                driveFolderId: string
            };
        };
        serverName: string;
    }
}

