import ZoomVideo from '@zoom/videosdk';

const client = ZoomVideo.createClient();

export async function initZoom() {
  await client.init('en-US', 'Global', { patchJsMedia: false });
  return client;
}

export default client;
