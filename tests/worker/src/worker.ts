import apiRouter from './router';

export type Env = {
	DB1: D1Database;
};

export default {
	async fetch(request: Request, env: Env, ctx: ExecutionContext): Promise<Response> {
		const url = new URL(request.url);

		if (url.pathname.startsWith('/api/')) {
			return apiRouter.handle(request, env);
		}

		return new Response('Route not found.', { status: 404 });
	},
};
