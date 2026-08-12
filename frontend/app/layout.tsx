import type { Metadata } from 'next';
import { Inter, Plus_Jakarta_Sans } from 'next/font/google';
import './globals.css';
import { AuthProvider } from '../hooks/useAuth';
import Navbar from '../components/Navbar';
import Footer from '../components/Footer';

const inter = Inter({ subsets: ['latin'], variable: '--font-inter' });
const jakarta = Plus_Jakarta_Sans({ subsets: ['latin'], variable: '--font-jakarta' });

export const metadata: Metadata = {
  title: 'DVS Realty | Luxury Real Estate & Premium Homes',
  description: 'Explore ultra-luxury penthouses, private beach villas, signature apartments, and prime commercial real estate across top metro locations.',
  keywords: ['real estate', 'luxury homes', 'penthouses', 'villas', 'apartments', 'buy property', 'rent property'],
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html lang="en" className={`${inter.variable} ${jakarta.variable} dark`}>
      <body className="bg-slate-950 text-slate-100 font-sans antialiased selection:bg-amber-400 selection:text-slate-950 flex flex-col min-h-screen">
        <AuthProvider>
          <Navbar />
          <main className="flex-1 pt-20">{children}</main>
          <Footer />
        </AuthProvider>
      </body>
    </html>
  );
}
