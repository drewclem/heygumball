require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET_KEY);

exports.handler = async (req, res) => {
  const initReq = JSON.parse(req.body);

  try {
    const session = await stripe.billingPortal.sessions.create({
      customer: initReq.customer,
      return_url: initReq.return_url,
    });

    return {
      statusCode: 200,
      body: JSON.stringify(session),
    };
  } catch (error) {
    return {
      statusCode: 500,
      body: error.toString(),
    };
  }
};
