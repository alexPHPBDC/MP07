import { EndBehaviorType, VoiceReceiver } from "@discordjs/voice";
import { AnyThreadChannel, ApplicationCommandDataResolvable, ApplicationCommandOptionType, CacheType, ChannelType, ChatInputCommandInteraction, Client, Events, GatewayIntentBits, GuildMember, GuildTextBasedChannel, Interaction, Message, MessageReaction, PartialMessage, PartialMessageReaction, PartialUser, User } from "discord.js";
import { pipeline } from 'node:stream';
import prism, { opus } from 'prism-media';
import { Database } from "./Database";
import { interactionHandlers } from "./Interactions";
import { Whisper } from "./Whisper";
import fs from "fs";
import { borrarFitxer } from "../utils";
const ffmpegStatic = require('ffmpeg-static');
const ffmpeg = require('fluent-ffmpeg');
const whisper = require('debug')("whisper");
// Tell fluent-ffmpeg where it can find FFmpeg
ffmpeg.setFfmpegPath(ffmpegStatic);
const bot = require('debug')("bot");
const discord = require('debug')("discord");
const error = require('debug')("error");
export class Discord {

    private static instance: Discord;
    private client: Client;
    private constructor() {
        this.client = new Client({
            intents: [
                GatewayIntentBits.Guilds,
                GatewayIntentBits.GuildMessages,
                GatewayIntentBits.MessageContent,
                GatewayIntentBits.GuildVoiceStates,
                GatewayIntentBits.GuildMessageReactions,
                GatewayIntentBits.GuildMembers,

            ],
        });

        this.client.login(process.env.TOKEN);
    }

    public static get Instance() {
        return this.instance || (this.instance = new this());
    }

    establirComands(client: Client = this.client) {

        const command: ApplicationCommandDataResolvable[] = [
            {
                name: "escoltar",
                description: "Escolta el canal de veu",
                options: [
                    {
                        name: "canal",
                        description: "El canal de veu",
                        type: ApplicationCommandOptionType.Channel, //Channel
                        required: true,
                        channel_types: [ChannelType.GuildVoice],
                    }
                ]
            }];

        client.application?.commands.set(command);

    }

    establirDiscordListeners() {

        this.client

            .once(Events.ClientReady, () => {
                discord(`${this.client?.user?.username} is online`);
                this.establirComands();
            })

            .on(Events.InteractionCreate, async (interaction: Interaction) => {

                if (!interaction.isCommand() || !interaction.guildId || !interaction.isChatInputCommand()) return;

                const handler = interactionHandlers.get(interaction.commandName);

                if (handler) {
                    handler(interaction, this.client).catch((e) => {
                        error("ERROR en escoltar:" + interaction.guildId + e);
                    });


                } else {
                    interaction.reply('Unknown command');
                }


            })

            .on(Events.MessageCreate, async (message: Message) => {

                let { today, serverID, channel, serverFolderName, thread } = this.preparedData(message);
                let log = "\n [" + message.createdAt.toUTCString() + "] Message created: " + message.content + " by " + message.author?.username;
                Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
            })

            .on(Events.MessageUpdate, (oldMessage: Message | PartialMessage, newMessage: Message | PartialMessage) => {

                //No vull tractar amb PartialMessage, ja que només tenen l'id.
                if (oldMessage instanceof Message && newMessage instanceof Message) {
                    let { today, serverID, channel, serverFolderName, thread } = this.preparedData(newMessage);
                    let log = "\n [" + newMessage.createdAt.toUTCString() + "] Message updated: " + oldMessage.content + " by " + oldMessage.author?.username + " at " + oldMessage.createdAt.toUTCString() + " to " + newMessage.content + " at " + newMessage.createdAt.toUTCString();
                    Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                }

            })

            .on(Events.MessageDelete, (message: Message | PartialMessage) => {

                //No vull tractar amb PartialMessage, ja que només tenen l'id.
                if (message instanceof Message) {
                    let { today, serverID, channel, serverFolderName, thread } = this.preparedData(message);
                    let log = "\n [" + new Date().toUTCString() + "] Message deleted: " + message.content + " by " + message.author?.username + " at " + message.createdAt.toUTCString();
                    Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                }

            })

            .on(Events.MessageReactionAdd, (reaction: MessageReaction | PartialMessageReaction, user: User | PartialUser) => {

                //No vull tractar amb res parcial, per tant, només actuo si no és parcial el què m'entra.
                if (reaction instanceof MessageReaction && user instanceof User && reaction.message instanceof Message) {
                    let { today, serverID, channel, serverFolderName, thread } = this.preparedData(reaction.message);
                    let log = "\n [" + new Date().toISOString() + "] Reaction " + reaction.emoji.name + " added by " + user.username + " on message:" + reaction.message.content + " from the user " + reaction.message?.author?.username;
                    Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                }

            })

            .on(Events.MessageReactionRemove, (reaction: MessageReaction | PartialMessageReaction, user: User | PartialUser) => {

                if (reaction instanceof MessageReaction && user instanceof User && reaction.message instanceof Message) {
                    let { today, serverID, channel, serverFolderName, thread } = this.preparedData(reaction.message);
                    let log = "\n Reaction removed: " + reaction.emoji.name + " by " + user.username + " at " + reaction.message.createdAt.toUTCString() + "from" + reaction.message.content;
                    Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                }

            })

            .on(Events.MessageReactionRemoveEmoji, (reaction: MessageReaction | PartialMessageReaction) => {

                if (reaction instanceof MessageReaction && reaction.message instanceof Message) {
                    let { today, serverID, channel, serverFolderName, thread } = this.preparedData(reaction.message);
                    let log = "\n Reaction removed: " + reaction.emoji.name + " at " + reaction.message.createdAt.toUTCString() + "from" + reaction.message.content;
                    Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                }
            })

            .on(Events.ThreadCreate, (thread: AnyThreadChannel) => {

                thread.fetchStarterMessage().then((message) => {

                    if (message) {
                        let log = "\n Thread created at message " + message.content;
                        let { today, serverID, channel, serverFolderName } = this.preparedData(message);
                        Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                    }
                }).catch((e) => {
                    error("ERROR en escoltar:" + thread.guildId + e);
                });

            })

            .on(Events.ThreadDelete, (thread: AnyThreadChannel) => {
                thread.fetchStarterMessage().then((message) => {
                    if (message) {
                        let log = "\n Thread that started at message " + message.content + " deleted";
                        let { today, serverID, channel, serverFolderName } = this.preparedData(message);
                        Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                    }
                }).catch((e) => {
                    error("ERROR en escoltar:" + thread.guildId + e);
                });


            });


    }

    createRecordingStream(receiver: VoiceReceiver, userId: string, user: User, interaction: ChatInputCommandInteraction<CacheType>) {

        if (!receiver.subscriptions.has(userId)) {
            const opusStream = receiver.subscribe(userId, {
                end: {
                    behavior: EndBehaviorType.AfterSilence,
                    duration: 2000,
                },
            });

            const oggStream = new opus.OggLogicalBitstream({
                opusHead: new opus.OpusHead({
                    channelCount: 2,
                    sampleRate: 48000,
                }),
                pageSizeControl: {
                    maxPackets: 10,
                },
            });

            let iniciGrabacio = Date.now();
            const path: fs.PathLike = `src/Recordings/${iniciGrabacio}-${this.getDisplayName(userId, user)}.ogg`;

            const out = fs.createWriteStream(path);

            pipeline(opusStream, oggStream, out, (err: any) => {
                if (err) {
                    error(`❌ Error recording file ${path} - ${err.message}`);
                } else {
                    discord(`Recorded ${path}`);
                }
            });

            out.on('finish', () => {
                opusStream.destroy;
                let dest = path.replace(".ogg", ".wav");
                if (out.bytesWritten >= (9366)) {

                    ffmpeg()
                        .addInput(path)
                        .saveToFile(dest)
                        .on('end', () => {
                            Whisper.convertirAText(dest).then((text: any) => {
                                console.log(text);
                                text = text.text;

                                if (text && interaction.guildId) {

                                    let today = new Date().toISOString().split('T')[0];
                                    let serverID = interaction.guildId;
                                    let channel = interaction.options.get('canal')!.channel ? interaction.options.get('canal')!.channel as GuildTextBasedChannel : undefined;
                                    let serverFolderName = interaction.guild?.name ?? "ERRORNAME" + "_" + serverID;
                                    let thread = undefined;

                                    if (channel) {
                                        let log = "\n [" + new Date().toISOString() + "] (Transcription): " + text + " by " + user.username + " at " + new Date(iniciGrabacio).toISOString();
                                        Database.Instance.guardarLocal(serverID, today, channel, serverFolderName, log, thread);
                                    }
                                }

                                whisper(text);
                            });
                            
                            borrarFitxer(path);
                            borrarFitxer(dest);
                        })
                        .on('error', (err: any) => {
                            error(err);
                            borrarFitxer(path);
                            borrarFitxer(dest);
                        });
                }
                out.destroy;
            }
            );
        }

    }

    getDisplayName(userId: string, user?: User) {
        return user ? `${user.username}_${user.discriminator}` : userId;
    }



    preparedData(message: Message) {
        let today = new Date().toISOString().split('T')[0];
        let serverID = message.guild?.id ?? "ERRORID";
        let serverFolderName = message.guild?.name ?? "ERRORNAME" + "_" + serverID;
        let channel: GuildTextBasedChannel = message.channel as GuildTextBasedChannel;
        let thread: AnyThreadChannel | undefined = undefined;
        let aux: AnyThreadChannel | undefined = undefined;

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



}
