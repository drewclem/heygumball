require("dotenv").config();
const mailgun = require("mailgun-js");

exports.handler = async (req, res) => {
  const apiKey = process.env.MAILGUN_PRIVATE_KEY;
  const domain = process.env.MAILGUN_DOMAIN;

  const mg = mailgun({ apiKey: apiKey, domain: domain });

  const initReq = JSON.parse(req.body);

  const data = {
    from: `Heygumball <${initReq.sender}>`,
    to: initReq.receiver,
    subject: initReq.subject,
    text: initReq.text,
  };

  try {
    const message = await mg.messages().send(data);

    return {
      statusCode: 200,
      body: JSON.stringify({ message: "Success" }),
    };
  } catch (error) {
    console.log(error.message);
    return { statusCode: 500, body: error.toString() };
  }
};
