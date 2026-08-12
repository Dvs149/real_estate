import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  images: {
    remotePatterns: [
      {
        protocol: 'http',
        hostname: '127.0.0.1',
      },
      {
        protocol: 'http',
        hostname: 'localhost',
      },
      {
        protocol: 'https',
        hostname: 'images.unsplash.com',
      },
    ],
    unoptimized: true,
  },
  async rewrites() {
    return [
      {
        source: '/api/:path*',
        destination: 'http://127.0.0.1:8000/api/:path*',
      },
      {
        source: '/storage/:path*',
        destination: 'http://127.0.0.1:8000/storage/:path*',
      },
      {
        source: '/sanctum/:path*',
        destination: 'http://127.0.0.1:8000/sanctum/:path*',
      },
    ];
  },
};

export default nextConfig;
