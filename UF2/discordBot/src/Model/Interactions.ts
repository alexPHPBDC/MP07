import { joinVoiceChannel,  } from '@discordjs/voice';
import { CacheType, ChatInputCommandInteraction, Client,  User } from 'discord.js';
import { Discord } from './Discord';

async function escoltar(
    interaction: ChatInputCommandInteraction<CacheType>,
    client: Client,
) {

    const connection = joinVoiceChannel({
        channelId: interaction.options.get('canal')!.channel!.id,
        guildId: interaction.guildId!,
        adapterCreator: interaction.guild!.voiceAdapterCreator,
        selfDeaf: false,
    });
        
    if (connection) {

        const receiver = connection.receiver;

        receiver.speaking.on('start', (userId) => {
            const user: User | undefined = client.users.cache.get(userId);
            if (user) {
                Discord.Instance.createRecordingStream(receiver, userId, user, interaction);
            }
        });



        interaction.reply({ ephemeral: true, content: 'Listening!' });
    } else {
        interaction.reply({ ephemeral: true, content: 'Join a voice channel and then try that again!' });
    }

        
}

export const interactionHandlers = new Map<
    string,
    (
        interaction: ChatInputCommandInteraction,
        client: Client,
    ) => Promise<void>
>();


interactionHandlers.set('escoltar', escoltar);