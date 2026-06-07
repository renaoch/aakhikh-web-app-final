import express from 'express';
import path from 'path';
import { fileURLToPath } from 'url';
import { createServer as createViteServer } from 'vite';
import { SESClient, SendEmailCommand } from '@aws-sdk/client-ses';
import dotenv from 'dotenv';

dotenv.config();

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

async function startServer() {
  const app = express();
  const PORT = 3000;

  app.use(express.json());

  // AWS SES Client
  let sesClient: SESClient | null = null;

  function getSESClient() {
    if (!sesClient) {
      const accessKeyId = process.env.AWS_ACCESS_KEY_ID;
      const secretAccessKey = process.env.AWS_SECRET_ACCESS_KEY;
      const region = process.env.AWS_REGION || 'us-east-1';

      if (!accessKeyId || !secretAccessKey) {
        throw new Error('AWS credentials missing (AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY)');
      }

      sesClient = new SESClient({
        region,
        credentials: {
          accessKeyId,
          secretAccessKey,
        },
      });
    }
    return sesClient;
  }

  // API Routes
  app.post('/api/send-newsletter', async (req, res) => {
    try {
      const { recipients, subject, body, isHtml } = req.body;
      const sender = process.env.SES_SENDER_EMAIL;

      if (!sender) {
        return res.status(400).json({ error: 'SES_SENDER_EMAIL not configured' });
      }

      if (!recipients || !Array.isArray(recipients) || recipients.length === 0) {
        return res.status(400).json({ error: 'Recipients list is required' });
      }

      const client = getSESClient();

      // For bulk emails in SES Sandbox, we might need individual commands or verified list
      // For this implementation, we'll send a single email to all recipients (BCC recommended for newsletters)
      const command = new SendEmailCommand({
        Destination: {
          ToAddresses: [sender], // Send to self
          BccAddresses: recipients, // BCC all subscribers
        },
        Message: {
          Body: {
            [isHtml ? 'Html' : 'Text']: {
              Charset: 'UTF-8',
              Data: body,
            },
          },
          Subject: {
            Charset: 'UTF-8',
            Data: subject,
          },
        },
        Source: sender,
      });

      await client.send(command);
      res.json({ success: true, message: `Newsletter sent to ${recipients.length} subscribers` });
    } catch (error: any) {
      console.error('SES Email Error:', error);
      res.status(500).json({ error: error.message || 'Failed to send email' });
    }
  });

  // Vite integration
  if (process.env.NODE_ENV !== 'production') {
    const vite = await createViteServer({
      server: { middlewareMode: true },
      appType: 'spa',
    });
    app.use(vite.middlewares);
  } else {
    const distPath = path.join(process.cwd(), 'dist');
    app.use(express.static(distPath));
    app.get('*', (req, res) => {
      res.sendFile(path.join(distPath, 'index.html'));
    });
  }

  app.listen(PORT, '0.0.0.0', () => {
    console.log(`Server running on http://localhost:${PORT}`);
  });
}

startServer();
