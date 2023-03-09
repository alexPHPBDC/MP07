import { AnyThreadChannel, GuildTextBasedChannel, Message } from 'discord.js';
import fs from 'fs';

export function saveJSON(filename:string,data:any){
    var newData = JSON.stringify(data);
    fs.writeFileSync(filename, newData);
}

export function loadJSON(filename:string) {

    return JSON.parse(fs.readFileSync(filename,'utf-8'));
}

export function verifyJSON(filename:string,json: any, serverID: string, today: string, channel: GuildTextBasedChannel, serverFolderName:any,thread: AnyThreadChannel |undefined = undefined) {
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
        }
    }

    saveJSON(filename,json);
    return json;
}