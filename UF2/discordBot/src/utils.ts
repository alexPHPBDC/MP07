import { jsonDatabase } from "./Interfaces/interfaces";
import fs from "fs";
const error = require('debug')("error");
export function isJsonDatabase(dadesLocals: any | jsonDatabase): dadesLocals is jsonDatabase {
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

export function borrarFitxer(path:string){
    fs.unlink(path, (err) => {
        if (err) {
            error(err);
        }

    });
}






