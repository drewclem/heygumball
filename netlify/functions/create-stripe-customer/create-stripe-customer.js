require("dotenv").config();
const stripe = require("stripe")(process.env.STRIPE_SECRET_KEY);

const { createClient } = require("@supabase/supabase-js");

const supabase = createClient(
  process.env.VUE_APP_SUPABASE_URL,
  process.env.SUPABASE_SERVICE_KEY
);

exports.handler = async (req, res) => {
  const initReq = JSON.parse(req.body);
  try {
    const customer = await stripe.customers.create({
      email: initReq.record.email,
    });

    const { data, error } = await supabase
      .from("profiles")
      .update({
        stripe_customer: customer.id,
      })
      .eq("id", initReq.record.id);

    return {
      statusCode: 200,
      body: JSON.stringify({ message: `Account linked for:${customer.id}` }),
    };
  } catch (error) {
    return { statusCode: 500, body: error.toString() };
  }
};
