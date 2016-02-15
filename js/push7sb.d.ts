/// <reference path="../typings/browser.d.ts" />
/**
 * Created by hina on 2016/02/13.
 */
declare module p7sb {
    /**
     * Push7 HEAD API Response
     *
     */
    interface Push7_HEAD {
        alias: string;
        domain: string;
        icon: string;
        name: string;
        subscribers: number;
        url: string;
        error?: string;
    }
    /**
     * self-update push7-jetpack-button count & url
     */
    let update: () => void;
}
