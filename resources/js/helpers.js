import vuetify from "./plugins/vuetify.js";
import router from "./plugins/vue-router/router.js";

export function usePlugins(app) {
    app.use(router);
    app.use(vuetify);
}
