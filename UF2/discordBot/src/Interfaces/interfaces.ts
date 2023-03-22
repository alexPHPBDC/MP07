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
                                    driveFolderId?: string | undefined;
                                    driveFileId?: string | undefined;
                                }
                            };
                            channelName: string;
                            content: string;
                            driveFolderId?: string | undefined;
                            driveFileId?: string | undefined;
                        };
                    };
                    driveFolderId?: string | undefined;
                };
                
            };
            driveFolderId?: string | undefined;
        };
    };
}