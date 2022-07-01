require("dotenv").config();
const formData = require("form-data");
const Mailgun = require("mailgun.js");

exports.handler = async (req, res) => {
  const apiKey = process.env.MAILGUN_PRIVATE_KEY;
  const domain = process.env.MAILGUN_DOMAIN;

  const mailgun = new Mailgun(formData);
  const mg = mailgun.client({ username: "api", key: apiKey });

  const initReq = JSON.parse(req.body);

  const data = {
    from: initReq.sender,
    to: initReq.receiver,
    subject: initReq.subject,
    text: initReq.text,
  };

  try {
    const { error } = await mg.messages.create(domain, data);

    return {
      statusCode: 200,
      body: JSON.stringify({ message: "Success" }),
    };
  } catch (error) {
    console.log(error.message);
    return { statusCode: 500, body: error.toString() };
  }
};
