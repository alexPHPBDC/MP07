import axios from "axios";
import fs from "fs";
import FormData from "form-data";

export class Whisper {
    private static instance: Whisper;
    private static token = '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';
    private static openAiURL = 'https://api.openai.com/v1/audio/transcriptions';
    private constructor() {

    }
    public static get Instance() {
        return this.instance || (this.instance = new this());
    }

    static async convertirAText(filename: string | Buffer) {

        const formData = new FormData();
        formData.append('file', fs.createReadStream(filename));
        formData.append('model', 'whisper-1');
        let text:string|null = await axios.post(Whisper.openAiURL, formData, {
            headers: {
                'Authorization': `Bearer ${Whisper.token}`,
                'Content-Type': `multipart/form-data`,
                ...formData.getHeaders()
            }
        }).then(response => {
            return response.data;
        }).catch(error => {
            return null
        });

        return text;
    }
}