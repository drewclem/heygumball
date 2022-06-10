import { supabase } from "../supabase";

export default function useSupabase() {
  const createCollection = async (data) => {
    const { startDate, endDate, currentUser } = data;

    const currentDate = new Date();
    const nowDate = formatDate(currentDate);

    const startDateFormatted = formatDate(startDate);
    const endDateFormatted = formatDate(endDate);

    const req = await supabase.from("collections").insert([
      {
        user_id: currentUser.id,
        full_name: currentUser.full_name,
        start_date: startDate !== null ? startDateFormatted : nowDate,
        end_date: endDate !== null ? endDateFormatted : null,
      },
    ]);
    return req;
  };

  return {
    createCollection,
  };
}

function formatDate(date) {
  const dateObj = new Date(date);

  const year = dateObj.getFullYear();
  const month = ("0" + (dateObj.getMonth() + 1)).slice(-2);
  const day = dateObj.getDate();

  return year + "-" + month + "-" + day;
}
