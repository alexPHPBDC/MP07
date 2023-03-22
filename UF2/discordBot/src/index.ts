import dotenv from "dotenv";
import { OAuth2Client } from "googleapis-common";
import { Database } from "./Model/Database";
dotenv.config();

import { Discord } from "./Model/Discord";
import { Drive } from "./Model/Drive";
const bot = require('debug')("bot");
const error = require('debug')("discord");
const discord = require('debug')("discord");
//Cada 5 minuts, sincronitzo les dades amb google drive

process.on('uncaughtException', function (err) {
    error("error desconegut:" + err);
});

try {
    Discord.Instance.establirDiscordListeners();
    discord("Listeners de discord establerts correctament");
} catch (e) {
    error("Error al establir els listeners de discord: " + e);
}

Drive.Instance.getOAuth().then((OAuth: OAuth2Client | undefined) => {

    if (OAuth !== undefined) {
        setInterval(async () => {

            Database.Instance.sincronitzarDadesLocalsAmbDrive(OAuth)
                .then(() => { bot("Sincronització amb google drive realitzada correctament"); })
                .catch(e => { error("Error al sincronitzar les dades amb google drive: " + e); });

        }, 10 * 1000);
    } else {
        error("No s'ha pogut obtenir l'OAuth de google drive");
    }
}).catch(e => { error("Error al obtenir l'OAuth de google drive: " + e); });

