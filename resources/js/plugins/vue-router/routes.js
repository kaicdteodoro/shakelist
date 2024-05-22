const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('../../components/App.vue')
    }
];

function getRoutes() {
    return routes;
}

export default getRoutes();
