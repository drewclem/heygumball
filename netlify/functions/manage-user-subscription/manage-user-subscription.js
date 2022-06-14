require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET_KEY);
const { createClient } = require("@supabase/supabase-js");

const supabase = createClient(
  process.env.VUE_APP_SUPABASE_URL,
  process.env.SUPABASE_SERVICE_KEY
);

exports.handler = async (req, res) => {
  const signature = req.headers["stripe-signature"];
  const signingSecret = process.env.STRIPE_WEBHOOK_SECRET;

  let event;

  try {
    event = stripe.webhooks.constructEvent(req.body, signature, signingSecret);

    switch (event.type) {
      case "customer.subscription.updated":
        await supabase
          .from("profiles")
          .update({
            subscription_active: true,
          })
          .eq("stripe_customer", event.data.object.customer);
        break;
      case "customer.subscription.deleted":
        await supabase
          .from("profiles")
          .update({
            subscription_active: false,
          })
          .eq("stripe_customer", event.data.object.customer);
    }

    console.log(event.type);
    return {
      statusCode: 200,
      body: JSON.stringify({ event }),
    };
  } catch (error) {
    console.log(error.message);
    return { statusCode: 400, body: error.toString() };
  }
};
