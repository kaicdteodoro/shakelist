import App from '../../components/App.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: App
    }
];

function getRoutes() {
    return routes;
}

export default getRoutes();
