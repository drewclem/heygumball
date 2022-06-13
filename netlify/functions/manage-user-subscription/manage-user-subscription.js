require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET_KEY);

exports.handler = async (req, res) => {
  const signature = req.headers["stripe-signature"];
  const signingSecret = process.env.STRIPE_WEBHOOK_SECRET;

  let event;

  try {
    event = stripe.webhooks.constructEvent(req.body, signature, signingSecret);

    console.log(event.data);
    return {
      statusCode: 200,
      body: JSON.stringify({ event: event }),
    };
  } catch (error) {
    console.log(error.message);
    return { statusCode: 400, body: error.toString() };
  }
};
