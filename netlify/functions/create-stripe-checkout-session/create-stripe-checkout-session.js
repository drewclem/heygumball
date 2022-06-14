require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET_KEY);

exports.handler = async (req, res) => {
  const data = JSON.parse(req.body);

  let session;

  try {
    const stripeSession = await stripe.checkout.sessions.create({
      customer: `${data.customerId}`,
      mode: "subscription",
      payment_method_types: ["card"],
      line_items: [
        {
          price: process.env.VUE_APP_STRIPE_PRODUCT_ID,
          quantity: 1,
        },
      ],
      success_url: `${process.env.VUE_APP_BASE_URL}/${data.username}/account`,
      cancel_url: `${process.env.VUE_APP_BASE_URL}/${data.username}/account`,
    });

    return {
      statusCode: 200,
      body: JSON.stringify(stripeSession),
    };
  } catch (error) {
    return {
      statusCode: 500,
      body: error.toString(),
    };
  }
};
