import home from './home.mjs';
import { assetPages } from './assets.mjs';
import { corePages } from './core.mjs';
import { contentPages } from './content.mjs';

export const pages = [home, ...assetPages, ...corePages, ...contentPages];
